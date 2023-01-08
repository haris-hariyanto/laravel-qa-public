<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    private $userID = 1;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->generateAboutPage();
        $this->generatePrivacyPolicyPage();
        $this->generateTermsAndConditionsPage();
        $this->generateDisclaimerPage();
        $this->generateDmcaPage();
    }

    private function generateAboutPage()
    {
        $content = view('stubs.pages.en.about')->render();
        Page::create([
            'slug' => 'about',
            'user_id' => $this->userID,
            'title' => 'About Us',
            'content' => $content,
            'status' => 'PUBLISHED',
        ]);
    }

    private function generatePrivacyPolicyPage()
    {
        $content = view('stubs.pages.en.privacy-policy')->render();
        Page::create([
            'slug' => 'privacy-policy',
            'user_id' => $this->userID,
            'title' => 'Privacy Policy',
            'content' => $content,
            'status' => 'PUBLISHED',
        ]);
    }

    private function generateTermsAndConditionsPage()
    {
        $content = view('stubs.pages.en.terms')->render();
        Page::create([
            'slug' => 'terms',
            'user_id' => $this->userID,
            'title' => 'Terms and Conditions',
            'content' => $content,
            'status' => 'PUBLISHED',
        ]);
    }

    private function generateDisclaimerPage()
    {
        $content = view('stubs.pages.en.disclaimer')->render();
        Page::create([
            'slug' => 'disclaimer',
            'user_id' => $this->userID,
            'title' => 'Disclaimer',
            'content' => $content,
            'status' => 'PUBLISHED',
        ]);
    }

    private function generateDmcaPage()
    {
        $content = view('stubs.pages.en.dmca')->render();
        Page::create([
            'slug' => 'dmca',
            'user_id' => $this->userID,
            'title' => 'DMCA Copyright',
            'content' => $content,
            'status' => 'PUBLISHED',
        ]);
    }
}
