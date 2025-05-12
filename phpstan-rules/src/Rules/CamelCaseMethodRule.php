<?php

namespace App\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<ClassMethod>
 */
class CamelCaseMethodRule implements Rule
{
    private array $exceptions = ['__construct', '__destruct', '__call', '__get', '__set', '__isset', '__unset', '__sleep', '__wakeup', '__toString', '__invoke', '__clone', '__debugInfo'];

    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $method_name = $node->name->name;

        // Skip magic methods
        if (in_array($method_name, $this->exceptions, true)) {
            return [];
        }

        // Get the current file path
        $file_path = $scope->getFile();

        // Skip test files
        if (mb_strpos($file_path, '/tests/') !== false && preg_match('/Test\.php$/', $file_path)) {
            return [];
        }

        // Get the class being analyzed
        $class_reflection = $scope->getClassReflection();
        if ($class_reflection !== null) {
            // Skip if the class extends TestCase (PHPUnit test class)
            foreach ($class_reflection->getParentClassesNames() as $parent_class) {
                if (mb_strpos($parent_class, 'TestCase') !== false || mb_strpos($parent_class, 'PHPUnit') !== false) {
                    return [];
                }
            }
        }

        // Check if method name follows camelCase format (first character lowercase, no underscores)
        if (!preg_match('/^[a-z][a-zA-Z0-9]*$/', $method_name)) {
            $error_message = sprintf('Method %s should be in camelCase format', $method_name);

            return [
                RuleErrorBuilder::message($error_message)->build(),
            ];
        }

        return [];
    }
}
