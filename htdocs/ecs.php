<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\AssignmentInConditionSniff;
use PhpCsFixer\Fixer\Alias\ArrayPushFixer;
use PhpCsFixer\Fixer\Alias\MbStrFunctionsFixer;
use PhpCsFixer\Fixer\Alias\ModernizeStrposFixer;
use PhpCsFixer\Fixer\Alias\SetTypeToCastFixer;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoWhitespaceBeforeCommaInArrayFixer;
use PhpCsFixer\Fixer\Basic\NonPrintableCharacterFixer;
use PhpCsFixer\Fixer\Casing\ConstantCaseFixer;
use PhpCsFixer\Fixer\Casing\LowercaseKeywordsFixer;
use PhpCsFixer\Fixer\Casing\LowercaseStaticReferenceFixer;
use PhpCsFixer\Fixer\Casing\MagicConstantCasingFixer;
use PhpCsFixer\Fixer\Casing\NativeFunctionCasingFixer;
use PhpCsFixer\Fixer\CastNotation\ModernizeTypesCastingFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassDefinitionFixer;
use PhpCsFixer\Fixer\ClassNotation\FinalClassFixer;
use PhpCsFixer\Fixer\ClassNotation\NoNullPropertyInitializationFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\ConstantNotation\NativeConstantInvocationFixer;
use PhpCsFixer\Fixer\ControlStructure\ControlStructureContinuationPositionFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\FopenFlagsFixer;
use PhpCsFixer\Fixer\FunctionNotation\LambdaNotUsedImportFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\NullableTypeDeclarationForDefaultNullValueFixer;
use PhpCsFixer\Fixer\FunctionNotation\ReturnTypeDeclarationFixer;
use PhpCsFixer\Fixer\FunctionNotation\SingleLineThrowFixer;
use PhpCsFixer\Fixer\FunctionNotation\VoidReturnFixer;
use PhpCsFixer\Fixer\LanguageConstruct\ExplicitIndirectVariableFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\OperatorLinebreakFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocOrderFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSummaryFixer;
use PhpCsFixer\Fixer\PhpTag\BlankLineAfterOpeningTagFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitConstructFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertInternalTypeFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMockFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMockShortWillReturnFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestCaseStaticMethodCallsFixer;
use PhpCsFixer\Fixer\ReturnNotation\NoUselessReturnFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictComparisonFixer;
use PhpCsFixer\Fixer\StringNotation\ExplicitStringVariableFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use PhpCsFixer\Fixer\Whitespace\CompactNullableTypehintFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayListItemNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayOpenerAndCloserNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\CodingStandard\Fixer\Strict\BlankLineAfterStrictTypesFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

require __DIR__ . '/vendor/autoload.php';

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        './src',
        './public/index.php',
    ]);

    $ecsConfig->sets(
        [
            SetList::ARRAY,
            SetList::CONTROL_STRUCTURES,
            SetList::STRICT,
            SetList::PSR_12,
            SetList::CLEAN_CODE,
            SetList::COMMON,
        ],
    );

    $ecsConfig->rule(ModernizeTypesCastingFixer::class);
    $ecsConfig->rule(FopenFlagsFixer::class);
    $ecsConfig->rule(NativeConstantInvocationFixer::class);
    $ecsConfig->rule(NullableTypeDeclarationForDefaultNullValueFixer::class);
    $ecsConfig->rule(VoidReturnFixer::class);
    $ecsConfig->rule(OperatorLinebreakFixer::class);
    $ecsConfig->ruleWithConfiguration(ClassAttributesSeparationFixer::class, [
        'elements' => ['property' => 'one', 'method' => 'one'],
    ]);
    $ecsConfig->ruleWithConfiguration(MethodArgumentSpaceFixer::class, [
        'on_multiline' => 'ignore', /* This is added for readability that can be decided by the developer. */
    ]);
    $ecsConfig->ruleWithConfiguration(ConcatSpaceFixer::class, [
        'spacing' => 'one',
    ]);
    $ecsConfig->ruleWithConfiguration(GeneralPhpdocAnnotationRemoveFixer::class, [
        'annotations' => ['copyright', 'category'],
    ]);
    $ecsConfig->ruleWithConfiguration(NoSuperfluousPhpdocTagsFixer::class, [
        'allow_unused_params' => true,
    ]);
    $ecsConfig->ruleWithConfiguration(PhpUnitDedicateAssertFixer::class, [
        'target' => 'newest',
    ]);
    $ecsConfig->ruleWithConfiguration(YodaStyleFixer::class, [
        'equal' => false,
        'identical' => false,
        'less_and_greater' => false,
    ]);
    $ecsConfig->rule(PhpdocLineSpanFixer::class);
    $ecsConfig->rule(PhpdocOrderFixer::class);
    $ecsConfig->rule(PhpUnitConstructFixer::class);
    $ecsConfig->rule(PhpUnitDedicateAssertInternalTypeFixer::class);
    $ecsConfig->rule(PhpUnitMockFixer::class);
    $ecsConfig->rule(PhpUnitMockShortWillReturnFixer::class);
    $ecsConfig->rule(PhpUnitTestCaseStaticMethodCallsFixer::class);
    $ecsConfig->rule(NoUselessReturnFixer::class);
    $ecsConfig->rule(DeclareStrictTypesFixer::class);
    $ecsConfig->rule(BlankLineBeforeStatementFixer::class);
    $ecsConfig->rule(BlankLineAfterStrictTypesFixer::class);
    $ecsConfig->rule(CompactNullableTypehintFixer::class);
    $ecsConfig->rule(ArraySyntaxFixer::class);
    $ecsConfig->rule(ConstantCaseFixer::class);
    $ecsConfig->rule(LowercaseKeywordsFixer::class);
    $ecsConfig->rule(StrictComparisonFixer::class);
    $ecsConfig->rule(MagicConstantCasingFixer::class);
    $ecsConfig->rule(NativeFunctionCasingFixer::class);
    $ecsConfig->ruleWithConfiguration(ClassDefinitionFixer::class, [
        'single_line' => true,
        'space_before_parenthesis' => true,
    ]);
    $ecsConfig->rule(NonPrintableCharacterFixer::class);
    $ecsConfig->rule(VisibilityRequiredFixer::class);
    $ecsConfig->rule(ArrayPushFixer::class);
    $ecsConfig->rule(MbStrFunctionsFixer::class);
    $ecsConfig->rule(ModernizeStrposFixer::class);
    $ecsConfig->rule(SetTypeToCastFixer::class);
    $ecsConfig->rule(NoWhitespaceBeforeCommaInArrayFixer::class);
    $ecsConfig->rule(LowercaseStaticReferenceFixer::class);
    $ecsConfig->rule(NoNullPropertyInitializationFixer::class);
    $ecsConfig->rule(LambdaNotUsedImportFixer::class);
    $ecsConfig->rule(ControlStructureContinuationPositionFixer::class);
    $ecsConfig->rule(ReturnTypeDeclarationFixer::class);
    $ecsConfig->rule(FinalClassFixer::class);

    $ecsConfig->skip(
        [
            ArrayOpenerAndCloserNewlineFixer::class => null,
            ArrayListItemNewlineFixer::class => null,
            SingleLineThrowFixer::class => null,
            SelfAccessorFixer::class => null,
            ExplicitIndirectVariableFixer::class => null,
            BlankLineAfterOpeningTagFixer::class => null,
            PhpdocSummaryFixer::class => null,
            ExplicitStringVariableFixer::class => null,
            StandaloneLineInMultilineArrayFixer::class => null,
            AssignmentInConditionSniff::class => null,
            PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer::class => null,
        ],
    );
};
