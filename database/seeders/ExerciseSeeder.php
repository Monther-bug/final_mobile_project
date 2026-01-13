<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Problem;
use App\Models\TestCase;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exercises = $this->getExercises();

        foreach ($exercises as $exerciseData) {
            $exercise = Exercise::create([
                'title' => $exerciseData['title'],
                'description' => $exerciseData['description'],
                'category' => $exerciseData['category'],
            ]);

            foreach ($exerciseData['problems'] as $problemData) {
                $problem = Problem::create([
                    'exercise_id' => $exercise->id,
                    'title' => $problemData['title'],
                    'content' => $problemData['content'],
                    'difficulty' => $problemData['difficulty'],
                    'hint' => $problemData['hint'] ?? null,
                    'function_name' => $problemData['function_name'],
                    'input_type' => $problemData['input_type'] ?? 'auto',
                ]);

                foreach ($problemData['test_cases'] as $testCase) {
                    TestCase::create([
                        'problem_id' => $problem->id,
                        'input' => $testCase['input'],
                        'expected_output' => $testCase['expected_output'],
                    ]);
                }
            }
        }
    }

    private function getExercises(): array
    {
        return [
            // Arrays Category
            [
                'title' => 'Array Fundamentals',
                'description' => 'Learn the basics of working with arrays including creation, access, and manipulation.',
                'category' => 'Arrays',
                'problems' => [
                    [
                        'title' => 'Sum of Array',
                        'content' => "Write a function called `sum_array` that takes an array of integers and returns the sum of all elements.\n\n**Function Signature:**\n```python\ndef sum_array(arr):\n    # Your code here\n```\n\n**Example:**\n- Input: [1, 2, 3, 4, 5]\n- Output: 15\n\n**Constraints:**\n- Array length: 1-1000\n- Elements: -1000 to 1000",
                        'difficulty' => 'easy',
                        'function_name' => 'sum_array',
                        'hint' => 'Use a loop to iterate through each element and add it to a running total, or use the built-in sum() function.',
                        'test_cases' => [
                            ['input' => '[1, 2, 3, 4, 5]', 'expected_output' => '15'],
                            ['input' => '[10, -5, 3]', 'expected_output' => '8'],
                            ['input' => '[0]', 'expected_output' => '0'],
                            ['input' => '[-1, -2, -3]', 'expected_output' => '-6'],
                        ],
                    ],
                    [
                        'title' => 'Find Maximum',
                        'content' => "Write a function called `find_max` that finds the maximum value in an array of integers.\n\n**Function Signature:**\n```python\ndef find_max(arr):\n    # Your code here\n```\n\n**Example:**\n- Input: [3, 7, 2, 9, 1]\n- Output: 9\n\n**Constraints:**\n- Array length: 1-1000\n- Elements: -10000 to 10000",
                        'difficulty' => 'easy',
                        'function_name' => 'find_max',
                        'hint' => 'Start with the first element as the maximum, then compare with each subsequent element.',
                        'test_cases' => [
                            ['input' => '[3, 7, 2, 9, 1]', 'expected_output' => '9'],
                            ['input' => '[-5, -2, -10]', 'expected_output' => '-2'],
                            ['input' => '[42]', 'expected_output' => '42'],
                        ],
                    ],
                    [
                        'title' => 'Reverse Array',
                        'content' => "Write a function called `reverse_array` that reverses an array.\n\n**Function Signature:**\n```python\ndef reverse_array(arr):\n    # Your code here\n```\n\n**Example:**\n- Input: [1, 2, 3, 4, 5]\n- Output: [5, 4, 3, 2, 1]\n\n**Constraints:**\n- Array length: 0-1000",
                        'difficulty' => 'easy',
                        'function_name' => 'reverse_array',
                        'hint' => 'Use slicing arr[::-1] or the reversed() function.',
                        'test_cases' => [
                            ['input' => '[1, 2, 3, 4, 5]', 'expected_output' => '[5,4,3,2,1]'],
                            ['input' => '[1, 2]', 'expected_output' => '[2,1]'],
                            ['input' => '[]', 'expected_output' => '[]'],
                        ],
                    ],
                    [
                        'title' => 'Find Average',
                        'content' => "Write a function called `find_average` that calculates the average of an array of numbers.\n\n**Function Signature:**\n```python\ndef find_average(arr):\n    # Your code here\n```\n\n**Example:**\n- Input: [1, 2, 3, 4, 5]\n- Output: 3.0\n\n**Constraints:**\n- Array length: 1-1000\n- Return a float",
                        'difficulty' => 'easy',
                        'function_name' => 'find_average',
                        'hint' => 'Divide the sum of all elements by the count of elements.',
                        'test_cases' => [
                            ['input' => '[1, 2, 3, 4, 5]', 'expected_output' => '3.0'],
                            ['input' => '[10, 20]', 'expected_output' => '15.0'],
                            ['input' => '[5]', 'expected_output' => '5.0'],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Two Pointer Technique',
                'description' => 'Master the two pointer technique for solving array problems efficiently.',
                'category' => 'Arrays',
                'problems' => [
                    [
                        'title' => 'Two Sum',
                        'content' => "Write a function called `two_sum` that finds two numbers in an array that add up to a target.\n\n**Function Signature:**\n```python\ndef two_sum(nums, target):\n    # Your code here\n```\n\n**Example:**\n- Input: nums=[2, 7, 11, 15], target=9\n- Output: [0, 1] (because nums[0] + nums[1] = 9)\n\n**Constraints:**\n- Each input has exactly one solution\n- You may not use the same element twice",
                        'difficulty' => 'medium',
                        'function_name' => 'two_sum',
                        'hint' => 'Consider using a hash map to store values you\'ve seen and their indices.',
                        'test_cases' => [
                            ['input' => 'nums=[2,7,11,15], target=9', 'expected_output' => '[0,1]'],
                            ['input' => 'nums=[3,2,4], target=6', 'expected_output' => '[1,2]'],
                            ['input' => 'nums=[3,3], target=6', 'expected_output' => '[0,1]'],
                        ],
                    ],
                    [
                        'title' => 'Container With Most Water',
                        'content' => "Write a function called `max_area` that finds the container that holds the most water.\n\n**Function Signature:**\n```python\ndef max_area(height):\n    # Your code here\n```\n\n**Example:**\n- Input: [1, 8, 6, 2, 5, 4, 8, 3, 7]\n- Output: 49",
                        'difficulty' => 'medium',
                        'function_name' => 'max_area',
                        'hint' => 'Use two pointers starting from both ends. Move the pointer with the smaller height inward.',
                        'test_cases' => [
                            ['input' => '[1, 8, 6, 2, 5, 4, 8, 3, 7]', 'expected_output' => '49'],
                            ['input' => '[1, 1]', 'expected_output' => '1'],
                            ['input' => '[4, 3, 2, 1, 4]', 'expected_output' => '16'],
                        ],
                    ],
                ],
            ],
            // Strings Category
            [
                'title' => 'String Basics',
                'description' => 'Learn fundamental string operations and manipulations.',
                'category' => 'Strings',
                'problems' => [
                    [
                        'title' => 'Reverse String',
                        'content' => "Write a function called `reverse_string` that reverses a string.\n\n**Function Signature:**\n```python\ndef reverse_string(s):\n    # Your code here\n```\n\n**Example:**\n- Input: \"hello\"\n- Output: \"olleh\"",
                        'difficulty' => 'easy',
                        'function_name' => 'reverse_string',
                        'hint' => 'Use slicing s[::-1] or join reversed characters.',
                        'test_cases' => [
                            ['input' => 'hello', 'expected_output' => 'olleh'],
                            ['input' => 'world', 'expected_output' => 'dlrow'],
                            ['input' => 'a', 'expected_output' => 'a'],
                        ],
                    ],
                    [
                        'title' => 'Valid Palindrome',
                        'content' => "Write a function called `is_palindrome` that checks if a string is a palindrome.\n\n**Function Signature:**\n```python\ndef is_palindrome(s):\n    # Your code here\n```\n\n**Example:**\n- Input: \"A man, a plan, a canal: Panama\"\n- Output: true\n\n**Note:** Consider only alphanumeric characters and ignore cases.",
                        'difficulty' => 'easy',
                        'function_name' => 'is_palindrome',
                        'hint' => 'Remove non-alphanumeric characters, convert to lowercase, then compare with its reverse.',
                        'test_cases' => [
                            ['input' => 'racecar', 'expected_output' => 'true'],
                            ['input' => 'hello', 'expected_output' => 'false'],
                            ['input' => 'a', 'expected_output' => 'true'],
                        ],
                    ],
                    [
                        'title' => 'Count Vowels',
                        'content' => "Write a function called `count_vowels` that counts the number of vowels in a string.\n\n**Function Signature:**\n```python\ndef count_vowels(s):\n    # Your code here\n```\n\n**Example:**\n- Input: \"Hello World\"\n- Output: 3",
                        'difficulty' => 'easy',
                        'function_name' => 'count_vowels',
                        'hint' => 'Convert to lowercase and check each character against a set of vowels.',
                        'test_cases' => [
                            ['input' => 'Hello World', 'expected_output' => '3'],
                            ['input' => 'AEIOU', 'expected_output' => '5'],
                            ['input' => 'xyz', 'expected_output' => '0'],
                        ],
                    ],
                ],
            ],
            // Loops Category
            [
                'title' => 'Loop Patterns',
                'description' => 'Master different loop patterns and iteration techniques.',
                'category' => 'Loops',
                'problems' => [
                    [
                        'title' => 'Factorial',
                        'content' => "Write a function called `factorial` that calculates the factorial of a number.\n\n**Function Signature:**\n```python\ndef factorial(n):\n    # Your code here\n```\n\n**Example:**\n- Input: 5\n- Output: 120 (5! = 5 × 4 × 3 × 2 × 1)\n\n**Constraints:** 0 <= n <= 12",
                        'difficulty' => 'easy',
                        'function_name' => 'factorial',
                        'hint' => 'Use a loop starting from 1 to n, multiplying each number. Remember that 0! = 1.',
                        'test_cases' => [
                            ['input' => '5', 'expected_output' => '120'],
                            ['input' => '0', 'expected_output' => '1'],
                            ['input' => '10', 'expected_output' => '3628800'],
                        ],
                    ],
                    [
                        'title' => 'Fibonacci',
                        'content' => "Write a function called `fibonacci` that returns the nth Fibonacci number.\n\n**Function Signature:**\n```python\ndef fibonacci(n):\n    # Your code here\n```\n\n**Example:**\n- Input: 7\n- Output: 13 (sequence: 0, 1, 1, 2, 3, 5, 8, 13...)\n\n**Constraints:** 0 <= n <= 30",
                        'difficulty' => 'easy',
                        'function_name' => 'fibonacci',
                        'hint' => 'Each number is the sum of the two preceding numbers.',
                        'test_cases' => [
                            ['input' => '7', 'expected_output' => '13'],
                            ['input' => '0', 'expected_output' => '0'],
                            ['input' => '10', 'expected_output' => '55'],
                        ],
                    ],
                    [
                        'title' => 'Is Prime',
                        'content' => "Write a function called `is_prime` that checks if a number is prime.\n\n**Function Signature:**\n```python\ndef is_prime(n):\n    # Your code here\n```\n\n**Example:**\n- Input: 17\n- Output: true",
                        'difficulty' => 'easy',
                        'function_name' => 'is_prime',
                        'hint' => 'Check if any number from 2 to √n divides n evenly.',
                        'test_cases' => [
                            ['input' => '17', 'expected_output' => 'true'],
                            ['input' => '15', 'expected_output' => 'false'],
                            ['input' => '2', 'expected_output' => 'true'],
                            ['input' => '1', 'expected_output' => 'false'],
                        ],
                    ],
                ],
            ],
            // Functions Category
            [
                'title' => 'Function Basics',
                'description' => 'Learn how to create and use functions effectively.',
                'category' => 'Functions',
                'problems' => [
                    [
                        'title' => 'Calculator',
                        'content' => "Write a function called `calculate` that performs basic arithmetic.\n\n**Function Signature:**\n```python\ndef calculate(a, b, op):\n    # Your code here\n```\n\n**Example:**\n- Input: a=10, b=5, op='+'\n- Output: 15\n\n**Operators:** +, -, *, /\n**Note:** Return 'Error' for division by zero.",
                        'difficulty' => 'easy',
                        'function_name' => 'calculate',
                        'hint' => 'Use if-else or match statement to handle different operators.',
                        'test_cases' => [
                            ['input' => "a=10, b=5, op='+'", 'expected_output' => '15'],
                            ['input' => "a=10, b=5, op='*'", 'expected_output' => '50'],
                            ['input' => "a=10, b=0, op='/'", 'expected_output' => 'Error'],
                        ],
                    ],
                    [
                        'title' => 'Temperature Converter',
                        'content' => "Write a function called `convert_temp` that converts temperature.\n\n**Function Signature:**\n```python\ndef convert_temp(temp, unit):\n    # Your code here\n```\n\n**Example:**\n- Input: temp=100, unit='C'\n- Output: 212 (Celsius to Fahrenheit)\n\n**Formulas:**\n- C to F: F = (C × 9/5) + 32\n- F to C: C = (F - 32) × 5/9",
                        'difficulty' => 'easy',
                        'function_name' => 'convert_temp',
                        'hint' => 'Check the unit parameter and apply the appropriate formula.',
                        'test_cases' => [
                            ['input' => "temp=100, unit='C'", 'expected_output' => '212.0'],
                            ['input' => "temp=32, unit='F'", 'expected_output' => '0.0'],
                            ['input' => "temp=0, unit='C'", 'expected_output' => '32.0'],
                        ],
                    ],
                ],
            ],
            // Math Category
            [
                'title' => 'Math Fundamentals',
                'description' => 'Solve mathematical problems with programming.',
                'category' => 'Math',
                'problems' => [
                    [
                        'title' => 'Power of Two',
                        'content' => "Write a function called `is_power_of_two` that checks if a number is a power of two.\n\n**Function Signature:**\n```python\ndef is_power_of_two(n):\n    # Your code here\n```\n\n**Example:**\n- Input: 16\n- Output: true (2^4 = 16)",
                        'difficulty' => 'easy',
                        'function_name' => 'is_power_of_two',
                        'hint' => 'A power of two in binary has only one bit set. Use n & (n-1) == 0 for positive n.',
                        'test_cases' => [
                            ['input' => '16', 'expected_output' => 'true'],
                            ['input' => '18', 'expected_output' => 'false'],
                            ['input' => '1', 'expected_output' => 'true'],
                        ],
                    ],
                    [
                        'title' => 'GCD',
                        'content' => "Write a function called `gcd` that finds the greatest common divisor.\n\n**Function Signature:**\n```python\ndef gcd(a, b):\n    # Your code here\n```\n\n**Example:**\n- Input: a=48, b=18\n- Output: 6",
                        'difficulty' => 'easy',
                        'function_name' => 'gcd',
                        'hint' => 'Use the Euclidean algorithm: GCD(a, b) = GCD(b, a mod b) until b becomes 0.',
                        'test_cases' => [
                            ['input' => 'a=48, b=18', 'expected_output' => '6'],
                            ['input' => 'a=100, b=25', 'expected_output' => '25'],
                            ['input' => 'a=17, b=13', 'expected_output' => '1'],
                        ],
                    ],
                ],
            ],
            // Algorithms Category
            [
                'title' => 'Sorting Algorithms',
                'description' => 'Implement and understand fundamental sorting algorithms.',
                'category' => 'Algorithms',
                'problems' => [
                    [
                        'title' => 'Bubble Sort',
                        'content' => "Write a function called `bubble_sort` that sorts an array using bubble sort.\n\n**Function Signature:**\n```python\ndef bubble_sort(arr):\n    # Your code here\n```\n\n**Example:**\n- Input: [64, 34, 25, 12, 22, 11, 90]\n- Output: [11, 12, 22, 25, 34, 64, 90]",
                        'difficulty' => 'medium',
                        'function_name' => 'bubble_sort',
                        'hint' => 'Use nested loops. The outer loop controls passes, the inner loop compares adjacent elements.',
                        'test_cases' => [
                            ['input' => '[64, 34, 25, 12, 22, 11, 90]', 'expected_output' => '[11,12,22,25,34,64,90]'],
                            ['input' => '[5, 4, 3, 2, 1]', 'expected_output' => '[1,2,3,4,5]'],
                            ['input' => '[1]', 'expected_output' => '[1]'],
                        ],
                    ],
                    [
                        'title' => 'Binary Search',
                        'content' => "Write a function called `binary_search` that finds an element in a sorted array.\n\n**Function Signature:**\n```python\ndef binary_search(arr, target):\n    # Your code here\n```\n\n**Example:**\n- Input: arr=[1,3,5,7,9,11], target=7\n- Output: 3 (index of target)\n\n**Note:** Return -1 if not found.",
                        'difficulty' => 'medium',
                        'function_name' => 'binary_search',
                        'hint' => 'Compare target with middle element. Search left half if smaller, right half if larger.',
                        'test_cases' => [
                            ['input' => 'arr=[1,3,5,7,9,11], target=7', 'expected_output' => '3'],
                            ['input' => 'arr=[1,3,5,7,9,11], target=1', 'expected_output' => '0'],
                            ['input' => 'arr=[1,3,5,7,9,11], target=6', 'expected_output' => '-1'],
                        ],
                    ],
                ],
            ],
            // Data Structures Category
            [
                'title' => 'Stack Operations',
                'description' => 'Learn stack data structure and its applications.',
                'category' => 'Data Structures',
                'problems' => [
                    [
                        'title' => 'Valid Parentheses',
                        'content' => "Write a function called `is_valid` that checks if parentheses are balanced.\n\n**Function Signature:**\n```python\ndef is_valid(s):\n    # Your code here\n```\n\n**Example:**\n- Input: \"()[]{}\"\n- Output: true\n\n**Valid brackets:** (), [], {}",
                        'difficulty' => 'easy',
                        'function_name' => 'is_valid',
                        'hint' => 'Use a stack. Push opening brackets, pop and check when you see closing brackets.',
                        'test_cases' => [
                            ['input' => '()[]{}', 'expected_output' => 'true'],
                            ['input' => '([)]', 'expected_output' => 'false'],
                            ['input' => '{[]}', 'expected_output' => 'true'],
                        ],
                    ],
                ],
            ],
            // Recursion Category
            [
                'title' => 'Recursion Basics',
                'description' => 'Understand and apply recursive problem-solving techniques.',
                'category' => 'Recursion',
                'problems' => [
                    [
                        'title' => 'Sum of Digits',
                        'content' => "Write a function called `sum_digits` that calculates sum of digits using recursion.\n\n**Function Signature:**\n```python\ndef sum_digits(n):\n    # Your code here\n```\n\n**Example:**\n- Input: 12345\n- Output: 15 (1+2+3+4+5)",
                        'difficulty' => 'easy',
                        'function_name' => 'sum_digits',
                        'hint' => 'Base case: if n < 10, return n. Recursive: return (n % 10) + sum_digits(n // 10).',
                        'test_cases' => [
                            ['input' => '12345', 'expected_output' => '15'],
                            ['input' => '999', 'expected_output' => '27'],
                            ['input' => '0', 'expected_output' => '0'],
                        ],
                    ],
                    [
                        'title' => 'Power Function',
                        'content' => "Write a function called `power` that calculates x^n using recursion.\n\n**Function Signature:**\n```python\ndef power(x, n):\n    # Your code here\n```\n\n**Example:**\n- Input: x=2, n=10\n- Output: 1024",
                        'difficulty' => 'medium',
                        'function_name' => 'power',
                        'hint' => 'Use the property: x^n = (x^(n/2))^2 for even n.',
                        'test_cases' => [
                            ['input' => 'x=2, n=10', 'expected_output' => '1024'],
                            ['input' => 'x=3, n=3', 'expected_output' => '27'],
                            ['input' => 'x=5, n=0', 'expected_output' => '1'],
                        ],
                    ],
                ],
            ],
            // Dynamic Programming Category
            [
                'title' => 'Dynamic Programming Intro',
                'description' => 'Introduction to dynamic programming concepts and techniques.',
                'category' => 'Dynamic Programming',
                'problems' => [
                    [
                        'title' => 'Climbing Stairs',
                        'content' => "Write a function called `climb_stairs` that counts ways to climb n stairs.\n\n**Function Signature:**\n```python\ndef climb_stairs(n):\n    # Your code here\n```\n\n**Example:**\n- Input: n=3\n- Output: 3 (1+1+1, 1+2, 2+1)\n\n**Note:** You can climb 1 or 2 steps at a time.",
                        'difficulty' => 'easy',
                        'function_name' => 'climb_stairs',
                        'hint' => 'This is similar to Fibonacci. ways(n) = ways(n-1) + ways(n-2).',
                        'test_cases' => [
                            ['input' => '3', 'expected_output' => '3'],
                            ['input' => '5', 'expected_output' => '8'],
                            ['input' => '1', 'expected_output' => '1'],
                        ],
                    ],
                ],
            ],
            // Bit Manipulation Category
            [
                'title' => 'Bit Manipulation Basics',
                'description' => 'Learn to work with binary operations and bit manipulation.',
                'category' => 'Bit Manipulation',
                'problems' => [
                    [
                        'title' => 'Single Number',
                        'content' => "Write a function called `single_number` that finds the element appearing once.\n\n**Function Signature:**\n```python\ndef single_number(nums):\n    # Your code here\n```\n\n**Example:**\n- Input: [4, 1, 2, 1, 2]\n- Output: 4\n\n**Note:** Every element appears twice except for one.",
                        'difficulty' => 'easy',
                        'function_name' => 'single_number',
                        'hint' => 'Use XOR operation. XOR of a number with itself is 0.',
                        'test_cases' => [
                            ['input' => '[4, 1, 2, 1, 2]', 'expected_output' => '4'],
                            ['input' => '[2, 2, 1]', 'expected_output' => '1'],
                            ['input' => '[1]', 'expected_output' => '1'],
                        ],
                    ],
                ],
            ],
            // Searching Category
            [
                'title' => 'Search Techniques',
                'description' => 'Learn various searching algorithms and techniques.',
                'category' => 'Searching',
                'problems' => [
                    [
                        'title' => 'Linear Search',
                        'content' => "Write a function called `linear_search` that finds an element using linear search.\n\n**Function Signature:**\n```python\ndef linear_search(arr, target):\n    # Your code here\n```\n\n**Example:**\n- Input: arr=[5, 3, 8, 4, 2], target=8\n- Output: 2\n\n**Note:** Return -1 if not found.",
                        'difficulty' => 'easy',
                        'function_name' => 'linear_search',
                        'hint' => 'Iterate through each element and compare with target.',
                        'test_cases' => [
                            ['input' => 'arr=[5,3,8,4,2], target=8', 'expected_output' => '2'],
                            ['input' => 'arr=[1,2,3], target=5', 'expected_output' => '-1'],
                            ['input' => 'arr=[7], target=7', 'expected_output' => '0'],
                        ],
                    ],
                ],
            ],
            // Sorting Category
            [
                'title' => 'Sorting Basics',
                'description' => 'Learn basic sorting algorithms.',
                'category' => 'Sorting',
                'problems' => [
                    [
                        'title' => 'Selection Sort',
                        'content' => "Write a function called `selection_sort` that sorts using selection sort.\n\n**Function Signature:**\n```python\ndef selection_sort(arr):\n    # Your code here\n```\n\n**Example:**\n- Input: [64, 25, 12, 22, 11]\n- Output: [11, 12, 22, 25, 64]",
                        'difficulty' => 'medium',
                        'function_name' => 'selection_sort',
                        'hint' => 'Find minimum in unsorted part and swap with first unsorted element.',
                        'test_cases' => [
                            ['input' => '[64, 25, 12, 22, 11]', 'expected_output' => '[11,12,22,25,64]'],
                            ['input' => '[5, 4, 3, 2, 1]', 'expected_output' => '[1,2,3,4,5]'],
                            ['input' => '[1]', 'expected_output' => '[1]'],
                        ],
                    ],
                ],
            ],
            // Trees Category  
            [
                'title' => 'Tree Basics',
                'description' => 'Learn fundamental tree operations.',
                'category' => 'Trees',
                'problems' => [
                    [
                        'title' => 'Tree Height',
                        'content' => "Write a function called `tree_height` that finds tree height from level representation.\n\n**Function Signature:**\n```python\ndef tree_height(nodes):\n    # Your code here\n```\n\n**Example:**\n- Input: [1, 2, 3, 4, 5] (5 nodes)\n- Output: 3 (height of complete binary tree)\n\n**Note:** Use formula: height = floor(log2(n)) + 1 for complete binary tree.",
                        'difficulty' => 'easy',
                        'function_name' => 'tree_height',
                        'hint' => 'For a complete binary tree, height = floor(log2(n)) + 1',
                        'test_cases' => [
                            ['input' => '[1, 2, 3, 4, 5]', 'expected_output' => '3'],
                            ['input' => '[1]', 'expected_output' => '1'],
                            ['input' => '[1, 2, 3, 4, 5, 6, 7]', 'expected_output' => '3'],
                        ],
                    ],
                ],
            ],
            // Graphs Category
            [
                'title' => 'Graph Basics',
                'description' => 'Learn fundamental graph concepts.',
                'category' => 'Graphs',
                'problems' => [
                    [
                        'title' => 'Count Edges',
                        'content' => "Write a function called `count_edges` that counts edges in an adjacency list.\n\n**Function Signature:**\n```python\ndef count_edges(adj_list):\n    # Your code here\n```\n\n**Example:**\n- Input: [[1,2], [0,2], [0,1]] (3 nodes connected)\n- Output: 3 (for undirected graph)",
                        'difficulty' => 'easy',
                        'function_name' => 'count_edges',
                        'hint' => 'Sum all connections and divide by 2 for undirected graphs.',
                        'test_cases' => [
                            ['input' => '[[1,2], [0,2], [0,1]]', 'expected_output' => '3'],
                            ['input' => '[[1], [0]]', 'expected_output' => '1'],
                            ['input' => '[[]]', 'expected_output' => '0'],
                        ],
                    ],
                ],
            ],
            // Hard Challenges Category
            [
                'title' => 'Hard Challenges',
                'description' => 'Advanced problems to test your algorithmic mastery.',
                'category' => 'Hard Challenges',
                'problems' => [
                    [
                        'title' => 'Median of Arrays',
                        'content' => "Write a function called `find_median_sorted_arrays` that finds the median of two sorted arrays.\n\n**Function Signature:**\n```python\ndef find_median_sorted_arrays(nums1, nums2):\n    # Your code here\n```\n\n**Example:**\n- Input: nums1=[1,3], nums2=[2]\n- Output: 2.0\n\n**Constraints:**\n- Arrays sorted in ascending order\n- Time complexity should be O(log(m+n))",
                        'difficulty' => 'hard',
                        'function_name' => 'find_median_sorted_arrays',
                        'hint' => 'Use binary search on partitions of the smaller array to find the correct cut.',
                        'test_cases' => [
                            ['input' => 'nums1=[1,3], nums2=[2]', 'expected_output' => '2.0'],
                            ['input' => 'nums1=[1,2], nums2=[3,4]', 'expected_output' => '2.5'],
                            ['input' => 'nums1=[0,0], nums2=[0,0]', 'expected_output' => '0.0'],
                        ],
                    ],
                    [
                        'title' => 'Trapping Rain Water',
                        'content' => "Write a function called `trap` that computes how much water can be trapped after raining.\n\n**Function Signature:**\n```python\ndef trap(height):\n    # Your code here\n```\n\n**Example:**\n- Input: [0,1,0,2,1,0,1,3,2,1,2,1]\n- Output: 6\n\n**Constraints:**\n- n non-negative integers representing elevation map",
                        'difficulty' => 'hard',
                        'function_name' => 'trap',
                        'hint' => 'For each element, find max left and max right. Water level is min(max_left, max_right) - height.',
                        'test_cases' => [
                            ['input' => '[0,1,0,2,1,0,1,3,2,1,2,1]', 'expected_output' => '6'],
                            ['input' => '[4,2,0,3,2,5]', 'expected_output' => '9'],
                            ['input' => '[1,1]', 'expected_output' => '0'],
                        ],
                    ],
                ],
            ],
        ];
    }
}
