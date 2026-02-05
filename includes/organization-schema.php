<?php
/**
 * Schéma Organization pour améliorer l'affichage dans Google Search
 * Ce schema aide Google à afficher le nom du site au-dessus de l'URL
 */

$organization_schema = [
    "@context" => "https://schema.org",
    "@type" => "Organization",
    "name" => "loi-jeanbrun-gouv",
    "alternateName" => "Dispositif Jeanbrun",
    "url" => "https://loi-jeanbrun-gouv.com",
    "logo" => "https://loi-jeanbrun-gouv.com/assets/favicon.svg",
    "description" => "Information officielle sur le dispositif Jeanbrun (Relance Logement) : le nouveau cadre fiscal pour l'investissement locatif en France depuis 2026.",
    "sameAs" => [
        // Ajoutez ici vos réseaux sociaux si vous en avez
    ]
];

// Si schema_json n'est pas déjà défini, on l'initialise
if (!isset($schema_json)) {
    $schema_json = json_encode($organization_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
