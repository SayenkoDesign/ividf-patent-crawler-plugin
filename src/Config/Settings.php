<?php
namespace IVIDF\Config;


class Settings
{
    protected $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function buildSettingsPage()
    {
    }
}