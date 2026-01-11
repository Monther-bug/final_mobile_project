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
                        'content' => "Write a function that takes an array of integers and returns the sum of all elements.\n\nExample:\nInput: [1, 2, 3, 4, 5]\nOutput: 15\n\nConstraints:\n- Array length: 1-1000\n- Elements: -1000 to 1000",
                        'difficulty' => 'easy',
                        'hint' => 'Use a loop to iterate through each element and add it to a running total.',
                        'test_cases' => [
                            ['input' => '[1, 2, 3, 4, 5]', 'expected_output' => '15'],
                            ['input' => '[10, -5, 3]', 'expected_output' => '8'],
                            ['input' => '[0]', 'expected_output' => '0'],
                        ],
                    ],
                    [
                        'title' => 'Find Maximum',
                        'content' => "Write a function that finds the maximum value in an array of integers.\n\nExample:\nInput: [3, 7, 2, 9, 1]\nOutput: 9\n\nConstraints:\n- Array length: 1-1000\n- Elements: -10000 to 10000",
                        'difficulty' => 'easy',
                        'hint' => 'Start with the first element as the maximum, then compare with each subsequent element.',
                        'test_cases' => [
                            ['input' => '[3, 7, 2, 9, 1]', 'expected_output' => '9'],
                            ['input' => '[-5, -2, -10]', 'expected_output' => '-2'],
                            ['input' => '[42]', 'expected_output' => '42'],
                        ],
                    ],
                    [
                        'title' => 'Reverse Array',
                        'content' => "Write a function that reverses an array in place.\n\nExample:\nInput: [1, 2, 3, 4, 5]\nOutput: [5, 4, 3, 2, 1]\n\nConstraints:\n- Array length: 0-1000",
                        'difficulty' => 'easy',
                        'hint' => 'Use two pointers, one at the start and one at the end, and swap elements while moving towards the center.',
                        'test_cases' => [
                            ['input' => '[1, 2, 3, 4, 5]', 'expected_output' => '[5, 4, 3, 2, 1]'],
                            ['input' => '[1, 2]', 'expected_output' => '[2, 1]'],
                            ['input' => '[]', 'expected_output' => '[]'],
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
                        'content' => "Given an array of integers and a target sum, find two numbers that add up to the target.\n\nReturn the indices of the two numbers.\n\nExample:\nInput: nums = [2, 7, 11, 15], target = 9\nOutput: [0, 1] (because nums[0] + nums[1] = 9)\n\nConstraints:\n- Each input has exactly one solution\n- You may not use the same element twice",
                        'difficulty' => 'medium',
                        'hint' => 'Consider using a hash map to store values you\'ve seen and their indices.',
                        'test_cases' => [
                            ['input' => 'nums=[2,7,11,15], target=9', 'expected_output' => '[0, 1]'],
                            ['input' => 'nums=[3,2,4], target=6', 'expected_output' => '[1, 2]'],
                            ['input' => 'nums=[3,3], target=6', 'expected_output' => '[0, 1]'],
                        ],
                    ],
                    [
                        'title' => 'Container With Most Water',
                        'content' => "Given n non-negative integers representing heights of vertical lines, find two lines that together with the x-axis form a container that holds the most water.\n\nExample:\nInput: [1, 8, 6, 2, 5, 4, 8, 3, 7]\nOutput: 49\n\nThe lines at index 1 and 8 form a container that can hold 49 units of water.",
                        'difficulty' => 'medium',
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
                        'content' => "Write a function that reverses a string.\n\nExample:\nInput: \"hello\"\nOutput: \"olleh\"\n\nConstraints:\n- String length: 0-10000\n- Contains printable ASCII characters",
                        'difficulty' => 'easy',
                        'hint' => 'You can convert the string to an array, reverse it, and join it back.',
                        'test_cases' => [
                            ['input' => 'hello', 'expected_output' => 'olleh'],
                            ['input' => 'world', 'expected_output' => 'dlrow'],
                            ['input' => 'a', 'expected_output' => 'a'],
                        ],
                    ],
                    [
                        'title' => 'Valid Palindrome',
                        'content' => "Check if a string is a palindrome, considering only alphanumeric characters and ignoring cases.\n\nExample:\nInput: \"A man, a plan, a canal: Panama\"\nOutput: true\n\nExample 2:\nInput: \"race a car\"\nOutput: false",
                        'difficulty' => 'easy',
                        'hint' => 'Remove non-alphanumeric characters, convert to lowercase, then compare with its reverse.',
                        'test_cases' => [
                            ['input' => 'A man, a plan, a canal: Panama', 'expected_output' => 'true'],
                            ['input' => 'race a car', 'expected_output' => 'false'],
                            ['input' => ' ', 'expected_output' => 'true'],
                        ],
                    ],
                    [
                        'title' => 'Count Vowels',
                        'content' => "Write a function that counts the number of vowels (a, e, i, o, u) in a string.\n\nExample:\nInput: \"Hello World\"\nOutput: 3\n\nNote: Count both uppercase and lowercase vowels.",
                        'difficulty' => 'easy',
                        'hint' => 'Convert to lowercase and check each character against a set of vowels.',
                        'test_cases' => [
                            ['input' => 'Hello World', 'expected_output' => '3'],
                            ['input' => 'AEIOU', 'expected_output' => '5'],
                            ['input' => 'xyz', 'expected_output' => '0'],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'String Patterns',
                'description' => 'Work with string patterns, matching, and advanced manipulations.',
                'category' => 'Strings',
                'problems' => [
                    [
                        'title' => 'Longest Substring Without Repeating',
                        'content' => "Find the length of the longest substring without repeating characters.\n\nExample:\nInput: \"abcabcbb\"\nOutput: 3 (The answer is \"abc\")\n\nExample 2:\nInput: \"bbbbb\"\nOutput: 1 (The answer is \"b\")",
                        'difficulty' => 'medium',
                        'hint' => 'Use a sliding window approach with a set to track characters in the current window.',
                        'test_cases' => [
                            ['input' => 'abcabcbb', 'expected_output' => '3'],
                            ['input' => 'bbbbb', 'expected_output' => '1'],
                            ['input' => 'pwwkew', 'expected_output' => '3'],
                        ],
                    ],
                    [
                        'title' => 'Valid Anagram',
                        'content' => "Given two strings s and t, return true if t is an anagram of s, and false otherwise.\n\nAn anagram is a word formed by rearranging the letters of another word.\n\nExample:\nInput: s = \"anagram\", t = \"nagaram\"\nOutput: true",
                        'difficulty' => 'easy',
                        'hint' => 'Count the frequency of each character in both strings and compare.',
                        'test_cases' => [
                            ['input' => 's=anagram, t=nagaram', 'expected_output' => 'true'],
                            ['input' => 's=rat, t=car', 'expected_output' => 'false'],
                            ['input' => 's=listen, t=silent', 'expected_output' => 'true'],
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
                        'content' => "Calculate the factorial of a given non-negative integer.\n\nFactorial of n (n!) = n × (n-1) × (n-2) × ... × 1\n\nExample:\nInput: 5\nOutput: 120 (5! = 5 × 4 × 3 × 2 × 1 = 120)\n\nConstraints:\n- 0 <= n <= 12",
                        'difficulty' => 'easy',
                        'hint' => 'Use a loop starting from 1 to n, multiplying each number. Remember that 0! = 1.',
                        'test_cases' => [
                            ['input' => '5', 'expected_output' => '120'],
                            ['input' => '0', 'expected_output' => '1'],
                            ['input' => '10', 'expected_output' => '3628800'],
                        ],
                    ],
                    [
                        'title' => 'Fibonacci Sequence',
                        'content' => "Return the nth number in the Fibonacci sequence.\n\nFibonacci: 0, 1, 1, 2, 3, 5, 8, 13, 21, ...\n\nExample:\nInput: 7\nOutput: 13 (0-indexed: F(7) = 13)\n\nConstraints:\n- 0 <= n <= 30",
                        'difficulty' => 'easy',
                        'hint' => 'Each number is the sum of the two preceding numbers. Use iteration or recursion.',
                        'test_cases' => [
                            ['input' => '7', 'expected_output' => '13'],
                            ['input' => '0', 'expected_output' => '0'],
                            ['input' => '10', 'expected_output' => '55'],
                        ],
                    ],
                    [
                        'title' => 'Prime Number Check',
                        'content' => "Determine if a given number is prime.\n\nA prime number is a natural number greater than 1 that has no positive divisors other than 1 and itself.\n\nExample:\nInput: 17\nOutput: true\n\nExample 2:\nInput: 15\nOutput: false (divisible by 3 and 5)",
                        'difficulty' => 'easy',
                        'hint' => 'Check if any number from 2 to √n divides n evenly.',
                        'test_cases' => [
                            ['input' => '17', 'expected_output' => 'true'],
                            ['input' => '15', 'expected_output' => 'false'],
                            ['input' => '2', 'expected_output' => 'true'],
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
                        'title' => 'Calculator Function',
                        'content' => "Create a calculator function that takes two numbers and an operator (+, -, *, /) and returns the result.\n\nExample:\nInput: 10, 5, '+'\nOutput: 15\n\nExample 2:\nInput: 10, 5, '/'\nOutput: 2\n\nHandle division by zero by returning 'Error'.",
                        'difficulty' => 'easy',
                        'hint' => 'Use a switch statement or if-else to handle different operators.',
                        'test_cases' => [
                            ['input' => '10, 5, +', 'expected_output' => '15'],
                            ['input' => '10, 5, *', 'expected_output' => '50'],
                            ['input' => '10, 0, /', 'expected_output' => 'Error'],
                        ],
                    ],
                    [
                        'title' => 'Temperature Converter',
                        'content' => "Write a function that converts temperature between Celsius and Fahrenheit.\n\nFormulas:\n- C to F: F = (C × 9/5) + 32\n- F to C: C = (F - 32) × 5/9\n\nExample:\nInput: 100, 'C'\nOutput: 212 (100°C = 212°F)",
                        'difficulty' => 'easy',
                        'hint' => 'Check the unit parameter and apply the appropriate formula.',
                        'test_cases' => [
                            ['input' => '100, C', 'expected_output' => '212'],
                            ['input' => '32, F', 'expected_output' => '0'],
                            ['input' => '0, C', 'expected_output' => '32'],
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
                        'content' => "Implement the bubble sort algorithm to sort an array of integers in ascending order.\n\nExample:\nInput: [64, 34, 25, 12, 22, 11, 90]\nOutput: [11, 12, 22, 25, 34, 64, 90]\n\nBubble sort repeatedly steps through the list, compares adjacent elements and swaps them if they are in the wrong order.",
                        'difficulty' => 'medium',
                        'hint' => 'Use nested loops. The outer loop controls passes, the inner loop compares adjacent elements.',
                        'test_cases' => [
                            ['input' => '[64, 34, 25, 12, 22, 11, 90]', 'expected_output' => '[11, 12, 22, 25, 34, 64, 90]'],
                            ['input' => '[5, 4, 3, 2, 1]', 'expected_output' => '[1, 2, 3, 4, 5]'],
                            ['input' => '[1]', 'expected_output' => '[1]'],
                        ],
                    ],
                    [
                        'title' => 'Binary Search',
                        'content' => "Implement binary search to find the index of a target value in a sorted array.\n\nReturn -1 if the target is not found.\n\nExample:\nInput: arr = [1, 3, 5, 7, 9, 11], target = 7\nOutput: 3\n\nConstraints:\n- Array is sorted in ascending order\n- All elements are unique",
                        'difficulty' => 'medium',
                        'hint' => 'Compare the target with the middle element. If target is smaller, search left half; if larger, search right half.',
                        'test_cases' => [
                            ['input' => 'arr=[1,3,5,7,9,11], target=7', 'expected_output' => '3'],
                            ['input' => 'arr=[1,3,5,7,9,11], target=1', 'expected_output' => '0'],
                            ['input' => 'arr=[1,3,5,7,9,11], target=6', 'expected_output' => '-1'],
                        ],
                    ],
                    [
                        'title' => 'Merge Sort',
                        'content' => "Implement the merge sort algorithm to sort an array.\n\nMerge sort is a divide-and-conquer algorithm that:\n1. Divides the array into two halves\n2. Recursively sorts each half\n3. Merges the sorted halves\n\nExample:\nInput: [38, 27, 43, 3, 9, 82, 10]\nOutput: [3, 9, 10, 27, 38, 43, 82]",
                        'difficulty' => 'hard',
                        'hint' => 'First implement a merge function that combines two sorted arrays, then use recursion to divide and conquer.',
                        'test_cases' => [
                            ['input' => '[38, 27, 43, 3, 9, 82, 10]', 'expected_output' => '[3, 9, 10, 27, 38, 43, 82]'],
                            ['input' => '[5, 2, 8, 1, 9]', 'expected_output' => '[1, 2, 5, 8, 9]'],
                            ['input' => '[1]', 'expected_output' => '[1]'],
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
                        'content' => "Given a string containing just the characters '(', ')', '{', '}', '[' and ']', determine if the input string is valid.\n\nA string is valid if:\n1. Open brackets are closed by the same type of brackets\n2. Open brackets are closed in the correct order\n\nExample:\nInput: \"()[]{}\"\nOutput: true\n\nExample 2:\nInput: \"([)]\"\nOutput: false",
                        'difficulty' => 'easy',
                        'hint' => 'Use a stack. Push opening brackets and pop when you see a closing bracket. Check if they match.',
                        'test_cases' => [
                            ['input' => '()[]{}', 'expected_output' => 'true'],
                            ['input' => '([)]', 'expected_output' => 'false'],
                            ['input' => '{[]}', 'expected_output' => 'true'],
                        ],
                    ],
                    [
                        'title' => 'Min Stack',
                        'content' => "Design a stack that supports push, pop, top, and retrieving the minimum element in constant time.\n\nImplement the MinStack class:\n- push(val) pushes the element onto the stack\n- pop() removes the element on top\n- top() gets the top element\n- getMin() retrieves the minimum element",
                        'difficulty' => 'medium',
                        'hint' => 'Maintain a second stack that keeps track of the minimum values.',
                        'test_cases' => [
                            ['input' => 'push(-2), push(0), push(-3), getMin()', 'expected_output' => '-3'],
                            ['input' => 'push(1), push(2), top()', 'expected_output' => '2'],
                            ['input' => 'push(3), push(1), pop(), getMin()', 'expected_output' => '3'],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Linked Lists',
                'description' => 'Master linked list operations and problem-solving techniques.',
                'category' => 'Data Structures',
                'problems' => [
                    [
                        'title' => 'Reverse Linked List',
                        'content' => "Reverse a singly linked list.\n\nExample:\nInput: 1 -> 2 -> 3 -> 4 -> 5\nOutput: 5 -> 4 -> 3 -> 2 -> 1\n\nConstraints:\n- List length: 0-5000\n- Node values: -5000 to 5000",
                        'difficulty' => 'easy',
                        'hint' => 'Use three pointers: prev, current, and next. Iterate through the list, reversing the direction of each link.',
                        'test_cases' => [
                            ['input' => '1->2->3->4->5', 'expected_output' => '5->4->3->2->1'],
                            ['input' => '1->2', 'expected_output' => '2->1'],
                            ['input' => '1', 'expected_output' => '1'],
                        ],
                    ],
                    [
                        'title' => 'Detect Cycle',
                        'content' => "Given a linked list, determine if it has a cycle.\n\nA cycle exists if some node can be reached again by continuously following the next pointer.\n\nExample:\nInput: 3 -> 2 -> 0 -> -4 (where -4 points back to 2)\nOutput: true\n\nExample 2:\nInput: 1 -> 2 (no cycle)\nOutput: false",
                        'difficulty' => 'medium',
                        'hint' => 'Use Floyd\'s cycle detection algorithm with slow and fast pointers.',
                        'test_cases' => [
                            ['input' => '3->2->0->-4->cycle(2)', 'expected_output' => 'true'],
                            ['input' => '1->2', 'expected_output' => 'false'],
                            ['input' => '1->cycle(1)', 'expected_output' => 'true'],
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
                        'content' => "Determine if a given integer is a power of two.\n\nExample:\nInput: 16\nOutput: true (2^4 = 16)\n\nExample 2:\nInput: 18\nOutput: false\n\nConstraints:\n- -2^31 <= n <= 2^31 - 1",
                        'difficulty' => 'easy',
                        'hint' => 'A power of two in binary has only one bit set. Use bit manipulation: n & (n-1) == 0 for positive n.',
                        'test_cases' => [
                            ['input' => '16', 'expected_output' => 'true'],
                            ['input' => '18', 'expected_output' => 'false'],
                            ['input' => '1', 'expected_output' => 'true'],
                        ],
                    ],
                    [
                        'title' => 'GCD (Greatest Common Divisor)',
                        'content' => "Find the greatest common divisor of two positive integers.\n\nExample:\nInput: 48, 18\nOutput: 6\n\nThe GCD of 48 and 18 is 6 (both are divisible by 6, and no larger number divides both).",
                        'difficulty' => 'easy',
                        'hint' => 'Use the Euclidean algorithm: GCD(a, b) = GCD(b, a mod b) until b becomes 0.',
                        'test_cases' => [
                            ['input' => '48, 18', 'expected_output' => '6'],
                            ['input' => '100, 25', 'expected_output' => '25'],
                            ['input' => '17, 13', 'expected_output' => '1'],
                        ],
                    ],
                    [
                        'title' => 'Roman to Integer',
                        'content' => "Convert a Roman numeral to an integer.\n\nSymbol values:\nI = 1, V = 5, X = 10, L = 50, C = 100, D = 500, M = 1000\n\nExample:\nInput: \"MCMXCIV\"\nOutput: 1994 (M=1000, CM=900, XC=90, IV=4)\n\nConstraints:\n- 1 <= num <= 3999",
                        'difficulty' => 'medium',
                        'hint' => 'If a smaller value appears before a larger value, subtract it. Otherwise, add it.',
                        'test_cases' => [
                            ['input' => 'MCMXCIV', 'expected_output' => '1994'],
                            ['input' => 'III', 'expected_output' => '3'],
                            ['input' => 'LVIII', 'expected_output' => '58'],
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
                        'content' => "Calculate the sum of digits of a positive integer using recursion.\n\nExample:\nInput: 12345\nOutput: 15 (1+2+3+4+5)\n\nConstraints:\n- 0 <= n <= 10^9",
                        'difficulty' => 'easy',
                        'hint' => 'Base case: if n < 10, return n. Recursive case: return (n % 10) + sumDigits(n / 10).',
                        'test_cases' => [
                            ['input' => '12345', 'expected_output' => '15'],
                            ['input' => '999', 'expected_output' => '27'],
                            ['input' => '0', 'expected_output' => '0'],
                        ],
                    ],
                    [
                        'title' => 'Power Function',
                        'content' => "Implement pow(x, n), which calculates x raised to the power n.\n\nExample:\nInput: x = 2, n = 10\nOutput: 1024\n\nExample 2:\nInput: x = 2, n = -2\nOutput: 0.25\n\nUse recursion for an efficient O(log n) solution.",
                        'difficulty' => 'medium',
                        'hint' => 'Use the property: x^n = (x^(n/2))^2 for even n, and x * x^(n-1) for odd n.',
                        'test_cases' => [
                            ['input' => 'x=2, n=10', 'expected_output' => '1024'],
                            ['input' => 'x=2, n=-2', 'expected_output' => '0.25'],
                            ['input' => 'x=3, n=3', 'expected_output' => '27'],
                        ],
                    ],
                    [
                        'title' => 'Generate Parentheses',
                        'content' => "Given n pairs of parentheses, write a function to generate all combinations of well-formed parentheses.\n\nExample:\nInput: n = 3\nOutput: [\"((()))\", \"(()())\", \"(())()\", \"()(())\", \"()()()\"]\n\nConstraints:\n- 1 <= n <= 8",
                        'difficulty' => 'hard',
                        'hint' => 'Use backtracking. Track the count of open and close parentheses used.',
                        'test_cases' => [
                            ['input' => '3', 'expected_output' => '["((()))","(()())","(())()","()(())","()()()"]'],
                            ['input' => '1', 'expected_output' => '["()"]'],
                            ['input' => '2', 'expected_output' => '["(())","()()"]'],
                        ],
                    ],
                ],
            ],
        ];
    }
}
