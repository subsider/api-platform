<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use App\Security\TokenGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    private const USERS = [
        [
            'username' => 'admin',
            'email'    => 'admin@blog.com',
            'name'     => 'Piotr Jura',
            'password' => 'secret123#',
            'roles'    => [User::ROLE_SUPERADMIN],
            'enabled'  => true,
        ],
        [
            'username' => 'john_doe',
            'email'    => 'john@blog.com',
            'name'     => 'John Doe',
            'password' => 'secret123#',
            'roles'    => [User::ROLE_ADMIN],
            'enabled'  => true,
        ],
        [
            'username' => 'rob_smith',
            'email'    => 'rob@blog.com',
            'name'     => 'Rob Smith',
            'password' => 'secret123#',
            'roles'    => [User::ROLE_WRITER],
            'enabled'  => true,
        ],
        [
            'username' => 'jenny_rowling',
            'email'    => 'jenny@blog.com',
            'name'     => 'Jenny Rowling',
            'password' => 'secret123#',
            'roles'    => [User::ROLE_WRITER],
            'enabled'  => true,
        ],
        [
            'username' => 'han_solo',
            'email'    => 'han@blog.com',
            'name'     => 'Han Solo',
            'password' => 'secret123#',
            'roles'    => [User::ROLE_EDITOR],
            'enabled'  => false,
        ],
        [
            'username' => 'jedi_knight',
            'email'    => 'jedi@blog.com',
            'name'     => 'Jedi Knight',
            'password' => 'secret123#',
            'roles'    => [User::ROLE_COMMENTATOR],
            'enabled'  => false,
        ],
    ];
    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param TokenGenerator $tokenGenerator
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, TokenGenerator $tokenGenerator)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker           = Factory::create();
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadBlogPosts(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++) {
            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setSlug($this->faker->slug);
            $blogPost->setPublished($this->faker->dateTimeThisYear);
            $blogPost->setContent($this->faker->realText());
            $blogPost->setAuthor($this->getRandomUserReference($blogPost));

            $this->setReference("blog_post_{$i}", $blogPost);

            $manager->persist($blogPost);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadComments(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++) {
            for ($j = 0; $j < rand(1, 10); $j++) {
                $comment = new Comment();
                $comment->setPublished($this->faker->dateTimeThisYear);
                $comment->setContent($this->faker->realText());
                $comment->setAuthor($this->getRandomUserReference($comment));
                $comment->setBlogPost($this->getReference("blog_post_{$i}"));

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userFixture) {
            $user = new User();
            $user->setUsername($userFixture['username']);
            $user->setEmail($userFixture['email']);
            $user->setName($userFixture['name']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $userFixture['password']));
            $user->setRoles($userFixture['roles']);
            $user->setEnabled($userFixture['enabled']);
            if (!$userFixture['enabled']) {
                $user->setConfirmationToken($this->tokenGenerator->getRandomSecureToken());
            }

            $this->addReference("user_{$userFixture['username']}", $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @return User
     */
    protected function getRandomUserReference($entity): User
    {
        $randomUser = self::USERS[rand(0, 5)];

        if ($entity instanceof BlogPost && !count(
                array_intersect(
                    $randomUser['roles'],
                    [
                        User::ROLE_SUPERADMIN,
                        User::ROLE_ADMIN,
                        User::ROLE_WRITER
                    ])
            )) {
            return $this->getRandomUserReference($entity);
        }

        if ($entity instanceof Comment && !count(
                array_intersect(
                    $randomUser['roles'],
                    [
                        User::ROLE_SUPERADMIN,
                        User::ROLE_ADMIN,
                        User::ROLE_WRITER,
                        User::ROLE_COMMENTATOR
                    ])
            )) {
            return $this->getRandomUserReference($entity);
        }

        return $this->getReference('user_' . $randomUser['username']);
    }
}
