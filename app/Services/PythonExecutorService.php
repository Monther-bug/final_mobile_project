<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class PythonExecutorService
{
    private int $timeout = 10; // seconds
    private string $tempDir;

    public function __construct()
    {
        $this->tempDir = storage_path('app/code_execution');
        if (!is_dir($this->tempDir)) {
            mkdir($this->tempDir, 0755, true);
        }
    }

    /**
     * Execute Python code with given input and function name
     * 
     * @param string $userCode The user's function definition
     * @param string $input The test case input
     * @param string|null $functionName The function to call (if null, expects code to handle I/O)
     * @param string $inputType How to parse input: 'auto', 'json', 'raw'
     */
    public function execute(string $userCode, string $input, ?string $functionName = null, string $inputType = 'auto'): array
    {
        $uniqueId = uniqid('code_', true);
        $codeFile = $this->tempDir . DIRECTORY_SEPARATOR . $uniqueId . '.py';

        try {
            // Security check
            $this->validateCode($userCode);
            
            // Build the complete Python script
            $fullCode = $this->buildExecutableCode($userCode, $input, $functionName, $inputType);
            
            // Write code to temporary file
            file_put_contents($codeFile, $fullCode);

            // Execute Python code
            $result = $this->runPython($codeFile);

            return $result;
        } catch (\Exception $e) {
            Log::error('Code execution error: ' . $e->getMessage());
            return [
                'success' => false,
                'output' => '',
                'error' => $e->getMessage(),
                'execution_time' => 0,
            ];
        } finally {
            // Clean up temporary files
            if (file_exists($codeFile)) {
                @unlink($codeFile);
            }
        }
    }

    /**
     * Validate code for dangerous operations
     */
    private function validateCode(string $code): void
    {
        $dangerousPatterns = [
            '/import\s+os\b/' => 'import os',
            '/import\s+subprocess/' => 'import subprocess',
            '/from\s+os\s+import/' => 'from os import',
            '/from\s+subprocess\s+import/' => 'from subprocess import',
            '/__import__\s*\(/' => '__import__()',
            '/\bexec\s*\(/' => 'exec()',
            '/\beval\s*\(/' => 'eval()',
            '/\bopen\s*\([^)]*[\'"][wa]/' => 'open() with write mode',
            '/\bcompile\s*\(/' => 'compile()',
        ];

        foreach ($dangerousPatterns as $pattern => $name) {
            if (preg_match($pattern, $code)) {
                throw new \Exception("Forbidden operation: {$name} is not allowed");
            }
        }
    }

    /**
     * Build the complete executable Python code
     */
    private function buildExecutableCode(string $userCode, string $input, ?string $functionName, string $inputType): string
    {
        // Escape the input for Python string
        $escapedInput = $this->escapeForPython($input);
        
        $code = "# -*- coding: utf-8 -*-\n";
        $code .= "import json\n";
        $code .= "import ast\n\n";
        
        // Add helper functions for parsing
        $code .= $this->getHelperFunctions();
        
        // Add user's code
        $code .= "\n# User's code\n";
        $code .= $userCode;
        $code .= "\n\n";
        
        // Add the execution wrapper
        $code .= "# Execution wrapper\n";
        $code .= "if __name__ == '__main__':\n";
        $code .= "    _test_input = '''{$escapedInput}'''\n";
        
        if ($functionName) {
            // Parse input and call the function
            $code .= $this->buildFunctionCallCode($functionName, $inputType);
        } else {
            // No function name - the code should handle its own I/O
            // Provide input() function that reads from _test_input
            $code .= "    _input_lines = _test_input.strip().split('\\n') if _test_input.strip() else []\n";
            $code .= "    _input_index = 0\n";
            $code .= "    def input(prompt=''):\n";
            $code .= "        global _input_index\n";
            $code .= "        if _input_index < len(_input_lines):\n";
            $code .= "            result = _input_lines[_input_index]\n";
            $code .= "            _input_index += 1\n";
            $code .= "            return result\n";
            $code .= "        return ''\n";
            $code .= "    # Code should produce output via print()\n";
        }
        
        return $code;
    }

    /**
     * Build code to call the user's function with parsed input
     */
    private function buildFunctionCallCode(string $functionName, string $inputType): string
    {
        $code = "    try:\n";
        $code .= "        _args = parse_input(_test_input, '{$inputType}')\n";
        $code .= "        if isinstance(_args, tuple):\n";
        $code .= "            _result = {$functionName}(*_args)\n";
        $code .= "        elif isinstance(_args, dict):\n";
        $code .= "            _result = {$functionName}(**_args)\n";
        $code .= "        else:\n";
        $code .= "            _result = {$functionName}(_args)\n";
        $code .= "        # Format output\n";
        $code .= "        if isinstance(_result, bool):\n";
        $code .= "            print(str(_result).lower())\n";
        $code .= "        elif isinstance(_result, list):\n";
        $code .= "            print(str(_result).replace(' ', ''))\n";
        $code .= "        elif _result is None:\n";
        $code .= "            pass\n";
        $code .= "        else:\n";
        $code .= "            print(_result)\n";
        $code .= "    except Exception as e:\n";
        $code .= "        print(f'Error: {e}')\n";
        
        return $code;
    }

    /**
     * Get helper functions for parsing input
     */
    private function getHelperFunctions(): string
    {
        return <<<'PYTHON'
def parse_input(input_str, input_type='auto'):
    """Parse test input into Python objects"""
    input_str = input_str.strip()
    
    if not input_str:
        return None
    
    # Check for multiple arguments (comma-separated at top level, or multiple lines)
    if input_type == 'raw':
        return input_str
    
    # Try to detect the format
    lines = input_str.split('\n')
    
    # If single line, try to parse as single value or multiple comma-separated args
    if len(lines) == 1:
        # Check for named parameters like "nums=[1,2,3], target=9"
        if '=' in input_str and not input_str.startswith('['):
            return parse_named_args(input_str)
        
        # Try parsing as Python literal
        try:
            return ast.literal_eval(input_str)
        except:
            pass
        
        # Check for comma-separated simple values
        if ',' in input_str and not input_str.startswith('[') and not input_str.startswith('('):
            parts = [p.strip() for p in input_str.split(',')]
            parsed_parts = []
            for part in parts:
                try:
                    parsed_parts.append(ast.literal_eval(part))
                except:
                    parsed_parts.append(part)
            return tuple(parsed_parts)
        
        return input_str
    
    # Multiple lines - parse each line
    parsed_lines = []
    for line in lines:
        line = line.strip()
        if line:
            try:
                parsed_lines.append(ast.literal_eval(line))
            except:
                parsed_lines.append(line)
    
    return tuple(parsed_lines) if len(parsed_lines) > 1 else parsed_lines[0]

def parse_named_args(input_str):
    """Parse named arguments like 'nums=[1,2,3], target=9'"""
    result = {}
    # Simple parser for key=value pairs
    current_key = None
    current_value = ''
    depth = 0
    
    i = 0
    while i < len(input_str):
        char = input_str[i]
        
        if char in '([{':
            depth += 1
            current_value += char
        elif char in ')]}':
            depth -= 1
            current_value += char
        elif char == '=' and depth == 0 and current_key is None:
            current_key = current_value.strip()
            current_value = ''
        elif char == ',' and depth == 0:
            if current_key:
                try:
                    result[current_key] = ast.literal_eval(current_value.strip())
                except:
                    result[current_key] = current_value.strip()
            current_key = None
            current_value = ''
        else:
            current_value += char
        i += 1
    
    # Don't forget the last pair
    if current_key and current_value.strip():
        try:
            result[current_key] = ast.literal_eval(current_value.strip())
        except:
            result[current_key] = current_value.strip()
    
    return result if result else input_str

PYTHON;
    }

    /**
     * Escape string for Python triple-quoted string
     */
    private function escapeForPython(string $str): string
    {
        // Escape backslashes and triple quotes
        $str = str_replace('\\', '\\\\', $str);
        $str = str_replace("'''", "\\'\\'\\'", $str);
        return $str;
    }

    /**
     * Run Python interpreter
     */
    private function runPython(string $codeFile): array
    {
        $startTime = microtime(true);
        
        $pythonCmd = $this->getPythonCommand();
        
        // Build command
        if (PHP_OS_FAMILY === 'Windows') {
            $command = sprintf('%s "%s" 2>&1', $pythonCmd, $codeFile);
        } else {
            $command = sprintf('timeout %d %s "%s" 2>&1', $this->timeout, $pythonCmd, $codeFile);
        }

        // Execute with timeout on Windows using proc_open
        if (PHP_OS_FAMILY === 'Windows') {
            $result = $this->executeWithTimeout($command, $this->timeout);
        } else {
            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);
            $result = [
                'output' => implode("\n", $output),
                'return_code' => $returnCode,
                'timed_out' => $returnCode === 124,
            ];
        }

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        if ($result['timed_out']) {
            return [
                'success' => false,
                'output' => '',
                'error' => 'Time Limit Exceeded (>' . $this->timeout . 's)',
                'execution_time' => $executionTime,
            ];
        }

        if ($result['return_code'] !== 0) {
            return [
                'success' => false,
                'output' => '',
                'error' => $result['output'] ?: 'Runtime error',
                'execution_time' => $executionTime,
            ];
        }

        return [
            'success' => true,
            'output' => trim($result['output']),
            'error' => '',
            'execution_time' => $executionTime,
        ];
    }

    /**
     * Execute command with timeout on Windows
     */
    private function executeWithTimeout(string $command, int $timeout): array
    {
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($command, $descriptors, $pipes);

        if (!is_resource($process)) {
            return ['output' => 'Failed to start process', 'return_code' => 1, 'timed_out' => false];
        }

        fclose($pipes[0]);

        // Set streams to non-blocking
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        $output = '';
        $stderr = '';
        $startTime = time();

        while (true) {
            $status = proc_get_status($process);
            
            if (!$status['running']) {
                // Process finished
                $output .= stream_get_contents($pipes[1]);
                $stderr .= stream_get_contents($pipes[2]);
                break;
            }

            if ((time() - $startTime) >= $timeout) {
                // Timeout - kill process
                proc_terminate($process, 9);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);
                return ['output' => '', 'return_code' => 1, 'timed_out' => true];
            }

            $output .= stream_get_contents($pipes[1]);
            $stderr .= stream_get_contents($pipes[2]);
            
            usleep(10000); // 10ms
        }

        fclose($pipes[1]);
        fclose($pipes[2]);
        $returnCode = proc_close($process);

        $fullOutput = $output . ($stderr ? "\n" . $stderr : '');

        return [
            'output' => trim($fullOutput),
            'return_code' => $returnCode,
            'timed_out' => false,
        ];
    }

    /**
     * Get Python command based on OS
     */
    private function getPythonCommand(): string
    {
        $commands = PHP_OS_FAMILY === 'Windows' 
            ? ['python', 'python3', 'py']
            : ['python3', 'python'];

        foreach ($commands as $cmd) {
            exec($cmd . ' --version 2>&1', $output, $returnCode);
            if ($returnCode === 0) {
                return $cmd;
            }
            $output = [];
        }

        throw new \Exception('Python interpreter not found. Please install Python.');
    }
}
