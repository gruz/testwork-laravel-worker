<?php

namespace Database\Factories;

use App\Models\Link;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Link::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'url' => $this->getUrl(),
            'status' => 'NEW',
        ];
    }

    private function getUrl()
    {
        static $urls = [
            'https://proxify.io',
            'https://reddit.com',
            'https://kavkazcenter.com',
            'https://airsoft.in.ua',
            'https://wire.com',
            'https://oun-upa.national.org.ua',
            'https://galiciadivision.ml',
            'https://opensuse.org',
            'https://protonmail.com',
            'http://komuvnyz.com',
            'https://nosite01.com/',
            'https://nosite02.com/',
            'https://nosite03.com/',
            'https://nosite04.com/',
            'https://nosite05.com/',
            'http://wyzoo.tk/',
            'https://httpstat.us/202',
            'https://httpstat.us/403',
            'https://httpstat.us/410',
        ];

        $url = $this->faker->randomElement($urls);

        $key = array_search($url, $urls);

        if(($key = array_search($url, $urls)) !== false) {
            unset($urls[$key]);
        }

        return $url;
    }
}
