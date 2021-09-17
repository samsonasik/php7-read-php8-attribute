<?php

use PhpParser\Error;
use PhpParser\ParserFactory;
use PhpParser\Node;
use PhpParser\Node\AttributeGroup;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

include 'vendor/autoload.php';

$code = <<<'CODE'
<?php

use Symfony\Component\Routing\Annotation\Route;

class SymfonyRoute
{
    #[Route(path: '/path', name: 'action')]
    public function action()
    {
    }
}

CODE;

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7, null, [
    'useIdentifierNodes' => true,
    'useConsistentVariableNodes' => true,
    'useExpressionStatements' => true,
    'useNopStatements' => false,
]);
try {
    $ast = $parser->parse($code);
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$nameResolver = new PhpParser\NodeVisitor\NameResolver;
$nodeTraverser = new NodeTraverser;
$nodeTraverser->addVisitor($nameResolver);

$nodeTraverser->addVisitor(new class extends NodeVisitorAbstract {
    public function enterNode(Node $node) {
        if ($node instanceof AttributeGroup) {
            foreach ($node->attrs as $key => $attribute) {
                if ($attribute instanceof PhpParser\Node\Attribute) {
                    $name = (string) $attribute->name;

                    echo 'Attribute name is' . PHP_EOL;

                    echo '--------------------------';

                    echo PHP_EOL;

                    echo $name;

                    echo PHP_EOL;

                    echo '-----------------------';
                }
            }
        }
    }
});

$ast = $nodeTraverser->traverse($ast);