<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        $user = $this->getReference('user_admin');

        $blogPost = new BlogPost();
        $blogPost->setTitle('A first blog post!');
        $blogPost->setSlug('a-first-blog-post');
        $blogPost->setPublished(new \DateTime('2018-01-09 12:00:00'));
        $blogPost->setContent('Content of the blog post');
        $blogPost->setAuthor($user);

        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle('A second blog post!');
        $blogPost->setSlug('a-second-blog-post');
        $blogPost->setPublished(new \DateTime('2018-01-09 12:00:00'));
        $blogPost->setContent('Content of the blog post');
        $blogPost->setAuthor($user);

        $manager->persist($blogPost);

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {
    }

    public function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@blog.com');
        $user->setName('Piotr Jura');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'secret123#'));

        $this->addReference('user_admin', $user);

        $manager->persist($user);
        $manager->flush();
    }
}
