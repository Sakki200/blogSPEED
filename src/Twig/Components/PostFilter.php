<?php

namespace App\Twig\Components;

use App\Repository\PostRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('PostFilter', template: 'components/PostFilter.html.twig')]
final class PostFilter
{
    use DefaultActionTrait;

    #[LiveProp(writable: true, url: true)]
    public ?string $query = null;

    public function __construct(private PostRepository $pr) {}

    public function getPosts(): array
    {
        if ($this->query) {
            return $this->pr->findByQuery($this->query);
        }

        return $this->pr->findAll();
    }
}
