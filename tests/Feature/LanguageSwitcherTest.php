<?php

namespace Tests\Feature;

use Tests\TestCase;

class LanguageSwitcherTest extends TestCase
{
    public function test_desktop_and_mobile_switchers_submit_only_their_own_locale_control(): void
    {
        $content = $this->get('/login')->assertOk()->getContent();
        $action = preg_quote(route('language.update'), '~');

        preg_match_all('~<form\b[^>]*action="'.$action.'"[^>]*>(.*?)</form>~s', $content, $forms);

        $this->assertCount(2, $forms[1]);
        $this->assertStringContainsString('<button', $forms[1][0]);
        $this->assertStringNotContainsString('<select', $forms[1][0]);
        $this->assertStringContainsString('<select', $forms[1][1]);
        $this->assertStringNotContainsString('<button', $forms[1][1]);
    }

    public function test_selected_language_is_saved_and_applied_on_the_next_request(): void
    {
        $this->from('/login')
            ->post(route('language.update'), ['locale' => 'en'])
            ->assertRedirect('/login')
            ->assertSessionHas('locale', 'en');

        $this->get('/login')
            ->assertOk()
            ->assertSee('Welcome back');
    }
}
