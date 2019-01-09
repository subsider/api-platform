<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $blogPost = new BlogPost();
        $blogPost->setTitle('A first blog post!');
        $blogPost->setSlug('a-first-blog-post');
        $blogPost->setPublished(new \DateTime('2018-01-09 12:00:00'));
        $blogPost->setContent('Content of the blog post');
        $blogPost->setAuthor('Piotr Jura');

        $manager->persist($blogPost);
        $manager->flush();
    }
}
