<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle::class => ['all' => true],
    JMS\SerializerBundle\JMSSerializerBundle::class => ['all' => true],
    Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle::class => ['all' => true],
    Nelmio\ApiDocBundle\NelmioApiDocBundle::class => ['all' => true],
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => ['all' => true],
    SymfonyCasts\Bundle\VerifyEmail\SymfonyCastsVerifyEmailBundle::class => ['all' => true],
    Nelmio\CorsBundle\NelmioCorsBundle::class => ['all' => true],
];
