<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia;

abstract class TestCase extends BaseTestCase
{
    /**
     * @return array<string, mixed>
     */
    protected function assertInertiaComponent(TestResponse $response, string $component): array
    {
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($component)->etc(),
        );

        return $response->inertiaProps();
    }

    protected function assertInertiaComponentContains(
        TestResponse $response,
        string $component,
        string ...$expectedValues,
    ): void {
        $props = $this->assertInertiaComponent($response, $component);
        $serializedProps = json_encode($props, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        foreach ($expectedValues as $expectedValue) {
            $this->assertStringContainsString($expectedValue, $serializedProps);
        }
    }

    protected function assertInertiaComponentDoesNotContain(
        TestResponse $response,
        string $component,
        string ...$unexpectedValues,
    ): void {
        $props = $this->assertInertiaComponent($response, $component);
        $serializedProps = json_encode($props, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        foreach ($unexpectedValues as $unexpectedValue) {
            $this->assertStringNotContainsString($unexpectedValue, $serializedProps);
        }
    }
}
