<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    // Les autres méthodes viendront ici

    public function send(string $from, string $to, string $subject, string $template, array $context): void
{
    $email = (new TemplatedEmail())
        ->from($from)
        ->to($to)
        ->subject($subject)
        ->htmlTemplate("emails/$template.html.twig")
        ->context($context);

    // On envoie le mail
    $this->mailer->send($email);
}

public function dire_bonjour_a_tous(): void
{
    // Suppose que vous avez une méthode pour obtenir tous les utilisateurs
    $users = $this->userRepository->findAll();

    foreach ($users as $user) {
        $this->send(
            'no-reply@monsite.net',
            $user->getEmail(),
            'Bonjour!',
            'bonjour_a_tous', // Assurez-vous d'avoir un template pour cela
            ['nom' => $user->getNom()]
        );
    }
}

public function dire_bonjour(User $user): void
{
    $this->send(
        'no-reply@monsite.net',
        $user->getEmail(),
        'Connexion détectée',
        'bonjour', // Assurez-vous d'avoir un template pour cela
        ['nom' => $user->getNom()]
    );
}

}

$this->sendMailService->send(
    'no-reply@monsite.net',
    'destinataire@monsite.net',
    'Titre de mon message',
    'test', // Assurez-vous que le fichier test.html.twig existe dans le dossier templates/emails
    ['prenom' => 'Prénom', 'nom' => 'Nom']
);
