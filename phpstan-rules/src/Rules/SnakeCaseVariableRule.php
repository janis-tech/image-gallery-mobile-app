<?php

namespace App\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Log;

/**
 * @implements Rule<Variable>
 */
class SnakeCaseVariableRule implements Rule
{
    public function getNodeType(): string
    {
        return Variable::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Node && is_string($node->name)) {

            Log::debug('Processing variable: ' . $node->name);

            // Skip special variables like $this
            if (in_array($node->name, ['this', '_'], true)) {
                return [];
            }

            // Skip variables that are already in snake_case
            if (preg_match('/^[a-z][a-z0-9_]*$/', $node->name)) {
                return [];
            }

            $error_message = sprintf('Variable $%s should be in snake_case format', $node->name);

            return [
                RuleErrorBuilder::message($error_message)->build(),
            ];
        }

        return [];
    }
}
