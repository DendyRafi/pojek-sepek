<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class BrowserConfirmationModalTest extends TestCase
{
    public function test_frontend_sources_do_not_use_browser_confirmation_dialogs(): void
    {
        $sourceFiles = [
            'resources/js/welcome.js',
            'resources/js/pengaturan.js',
            'resources/views/pengaturan/index.blade.php',
        ];

        foreach ($sourceFiles as $sourceFile) {
            $source = file_get_contents($this->sourcePath($sourceFile));

            $this->assertIsString($source);
            $this->assertStringNotContainsString('window.confirm', $source, "{$sourceFile} masih memakai confirm browser.");
            $this->assertStringNotContainsString('window.alert', $source, "{$sourceFile} masih memakai dialog browser.");
            $this->assertStringNotContainsString('onsubmit="return', $source, "{$sourceFile} masih memakai konfirmasi inline.");
        }
    }

    public function test_custom_confirmation_modal_assets_are_registered(): void
    {
        $welcomeScript = file_get_contents($this->sourcePath('resources/js/welcome.js'));
        $settingsScript = file_get_contents($this->sourcePath('resources/js/pengaturan.js'));
        $welcomeStyles = file_get_contents($this->sourcePath('resources/css/welcome.css'));
        $settingsStyles = file_get_contents($this->sourcePath('resources/css/pengaturan.css'));

        $this->assertIsString($welcomeScript);
        $this->assertIsString($settingsScript);
        $this->assertStringContainsString("import { confirmAction } from './confirm-modal';", $welcomeScript);
        $this->assertStringContainsString("import { confirmAction } from './confirm-modal';", $settingsScript);

        $this->assertIsString($welcomeStyles);
        $this->assertIsString($settingsStyles);
        $this->assertStringContainsString("@import './confirm-modal.css';", $welcomeStyles);
        $this->assertStringContainsString("@import './confirm-modal.css';", $settingsStyles);
    }

    public function test_skin_card_border_light_animations_are_defined(): void
    {
        $welcomeStyles = file_get_contents($this->sourcePath('resources/css/welcome.css'));

        $this->assertIsString($welcomeStyles);
        $this->assertStringContainsString('.skin-card.class-skin-item::before', $welcomeStyles);
        $this->assertStringContainsString('.skin-card.class-skin-item::after', $welcomeStyles);
        $this->assertStringContainsString('animation: borderLightTop', $welcomeStyles);
        $this->assertStringContainsString('animation: borderLightBottom', $welcomeStyles);
        $this->assertStringContainsString('@keyframes borderLightTop', $welcomeStyles);
        $this->assertStringContainsString('@keyframes borderLightBottom', $welcomeStyles);
    }

    private function sourcePath(string $path): string
    {
        return dirname(__DIR__, 2).DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $path);
    }
}
