<?php
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src');

return (new PhpCsFixer\Config())->setRules([
    '@PSR12' => true,
    '@PHP82Migration' => true,
    '@PhpCsFixer' => true,
    '@PHPUnit100Migration:risky' => true,
    'array_syntax' => ['syntax' => 'short'],
    'no_blank_lines_after_class_opening' => false,
    'global_namespace_import' => ['import_classes' => true, 'import_constants' => false, 'import_functions' => false],
    'ordered_imports' => ['imports_order' => ['class', 'function', 'const'], 'sort_algorithm' => 'none'],
    'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
    'concat_space' => ['spacing' => 'one'],
    'blank_line_before_statement' => false,
    'ordered_class_elements' => false,
    'php_unit_test_class_requires_covers' => false,
    'php_unit_internal_class' => false,
    'yoda_style' => false,
    'new_with_braces' => false,
    'function_declaration' => ['closure_function_spacing' => 'one', 'closure_fn_spacing' => 'one'],
    'increment_style' => false,
    'single_quote' => ['strings_containing_single_quote_chars' => false],
//    https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/index.rst#phpdoc
    'no_superfluous_phpdoc_tags' => ['allow_mixed' => true],
    'phpdoc_var_without_name' => false,
    'phpdoc_no_empty_return' => false,
    'phpdoc_to_comment' => false,
    'phpdoc_align' => ['align' => 'left'],
    'phpdoc_types_order' => false,
    'phpdoc_summary' => false,
])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
