<?php

declare(strict_types=1);

namespace Src\ThirdPartyServices\Firecrawl\Application\Fetch;

use Symfony\Component\DomCrawler\Crawler;

final readonly class FetchWebpageContentResponse
{
    public function __construct(
        public string $html,
        public ?string $title,
        public ?string $employmentType,
        public ?string $description,
        public ?array $location
    ) {}

    public static function fromHtml(string $html): self
    {
        $crawler = new Crawler($html);
        $title = null;
        $employmentType = null;
        $description = null;
        $location = null;

        try {
            $crawler->filter('script[type="application/ld+json"]')->each(function (Crawler $node) use (&$title, &$employmentType, &$description, &$location): void {
                $jsonContent = trim($node->text());
            
                $data = json_decode($jsonContent, true);

                if (json_last_error() === JSON_ERROR_NONE && isset($data['description'])) {
                    
                    $title = $data['title'] ?? null;
                    
                }
                if (json_last_error() === JSON_ERROR_NONE && isset($data['employmentType'])) {
                    
                    $employmentType = $data['employmentType'];
                    
                }
                if (json_last_error() === JSON_ERROR_NONE && isset($data['description'])) {
                    
                    $description = $data['description'];
                    
                }
                if (json_last_error() === JSON_ERROR_NONE && isset($data['jobLocation'])) {
                    
                    $location = $data['jobLocation']["address"] ?? null;
                    
                }
            });
        } catch (\Exception) {
            // If parsing fails, keep fields as null
        }

        return new self($html, $title, $employmentType, $description, $location);
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'employmentType' => $this->employmentType,
            'description' => $this->description,
            'location' => $this->location,
        ];
    }
}