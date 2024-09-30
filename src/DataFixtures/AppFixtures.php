<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\Entity\Label;
use App\Entity\Author;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $author = new Author();
        $author
            ->setEmail('admin@blog.fr')
            ->setFirstname('Martin')
            ->setLastname('LeGeek')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('$2y$13$atXrnoJfi6oSlA/zOVKaWulx5GOmLhm4q2w.REXVDcUiVWSbVdssu')
        ;
        $manager->persist($author);

        // 

        $categories = [
            'High Tech' => '-blue-500',
            'Code' => '-green-500',
            'Artificial Intelligence' => '-yellow-500',
            'Audiovisual' => '-violet-500'
        ];

        $tags = [
            'HighTech' => [
                'Smartphone',
                'Computer',
                'Tablet',
                'Robotics',
                'Home Automation',
                'Virtual Reality',
                'IoT',
                'Wearable',
                'Drone',
                '5G',
                'Blockchain',
                'Cybersecurity',
                'Cloud Computing',
            ],
            'Code' => [
                'PHP',
                'JavaScript',
                'Python',
                'Java',
                'C++',
                'Ruby',
                'Go',
                'TypeScript',
                'Rust',
                'Swift',
                'Kotlin',
                'SQL',
                'NoSQL',
                'API',
                'Framework',
            ],
            'ArtificialIntelligence' => [
                'Machine Learning',
                'Deep Learning',
                'Neural Networks',
                'NLP',
                'Computer Vision',
                'Chatbot',
                'Big Data',
                'Data Science',
                'Algorithm',
                'Automated Learning',
                'Voice Recognition',
                'Image Processing',
                'Prediction',
            ],
            'Audiovisual' => [
                'Cinema',
                'Television',
                'Streaming',
                'Podcast',
                'Radio',
                'Video Editing',
                'Animation',
                'VFX',
                'Sound',
                'Lighting',
                'Production',
                'Directing',
                'Screenwriting'
            ]
        ];

        $catArray = [];
        foreach ($categories as $name => $color) {
            $category = new Category();
            $category
                ->setName($name)
                ->setColor($color)
            ;
            $manager->persist($category);
            array_push($catArray, $category);
        }


        $labelArray = [];
        foreach ($tags as $tag => $list) {
            foreach ($list as $name) {
                $label = new Label();
                $label
                    ->setName($tag)
                ;
                $manager->persist($label);
                array_push($labelArray, $label);
            }
        }


        // Create posts
        for ($i=0; $i < 300; $i++) {
            $title = $faker->words(3, true);
            $content = $faker->paragraphs(3, true);
            $post = new Post();
            $post
                ->setTitle($title)
                ->setSlug($this->slugger->slug($title))
                ->setExcerpt(substr($content, 0, 250))
                ->setContent($content)
                ->setImage($faker->imageUrl(200, 200, 'technology'))
                ->setAuthor($author)
                ->setCategory($faker->randomElement($catArray))
                ->addLabel($faker->randomElement($labelArray))
                ->addLabel($faker->randomElement($labelArray))
                ->addLabel($faker->randomElement($labelArray))
                ;
            $manager->persist($post);
        }

        $manager->flush();
    }
}
