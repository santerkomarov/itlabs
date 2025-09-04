<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\OpenApi;

/**
 * Убирает Error*, ConstraintViolation* в Swagger в блоке "Schemas"
 */
class EditSchemasOpenApi implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated) {}

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);

        $components = $openApi->getComponents();
        if (null === $components || !method_exists($components, 'getSchemas')) {
            return $openApi;
        }

        $schemas = $components->getSchemas(); 
        if ($schemas instanceof \ArrayObject) {
            foreach (array_keys((array) $schemas) as $name) {
                if (preg_match('/^(Error(\.jsonld)?|ConstraintViolation.*)$/', $name)) {
                    unset($schemas[$name]);
                }
            }

            $components = $components->withSchemas($schemas);
            return $openApi->withComponents($components);
        }

        if (is_array($schemas)) {
            $filtered = array_filter(
                $schemas,
                static fn ($schema, string $name): bool =>
                    !preg_match('/^(Error(\.jsonld)?|ConstraintViolation.*)$/', $name),
                ARRAY_FILTER_USE_BOTH
            );

            $components = $components->withSchemas(new \ArrayObject($filtered));
            return $openApi->withComponents($components);
        }

        return $openApi;
    }
}
