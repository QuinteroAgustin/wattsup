# 🤙 WattSup

## 🚀 Introduction

**WattSup** est une application de ressemblant à l'application WhatsApp pour l'association AlHambra.

---

## 📂 Structure du Projet

WattUp
* ├── templates/ # Interface utilisateur (twig) 
* ├── src/ # API et logique métier (php) 

---

## 🌐 Application

### **Installation et Exécution**

1. Rendez-vous dans **symfony** :
   ```bash
   https://symfony.com/download

2. Installez les dépendances :
   ```bash
   Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
   Invoke-RestMethod -Uri https://get.scoop.sh | Invoke-Expression
   scoop install symfony-cli

3. Télécharger mercure :
   ```bash
   https://github.com/dunglas/mercure/releases/download/v0.16.3/mercure_Windows_x86_64.zip

4. Installer mercure (En powershell dans le dossier ou on a Unzip mercure) :
   ```bash
   $env:MERCURE_PUBLISHER_JWT_KEY='!ChangeThisMercureHubJWTSecretKey!'; 
   $env:MERCURE_SUBSCRIBER_JWT_KEY='!ChangeThisMercureHubJWTSecretKey!'; .\mercure.exe run --config dev.Caddyfile

5. Installer au préalable
   - [Composer](https://getcomposer.org/download/)
   - [NodeJS](https://nodejs.org/en/download/package-manager/current)

6. Démarer le serveur

7. Ouvrir le projet avec VS Code

8. Se placer dans le dossier du projet

9. Faire un 
   ```bash
   git clone git@github.com:QuinteroAgustin/wattsup.git

10. Se placer dans le dossier que git a créer
   cd wattsup

11. Installer composer dans le dossier
   composer install

12. Installer NPM
   npm install --global yarn
   npm i –force

13. Start le serveur (dans deux terminaux)
   npm run watch
   symfony server:start

14. Installer la BDD (vide)
   php bin/console make:migration
   php bin/console doctrine:migrations:migrate

15. Installer notre BDD
   - [BASE.sql](https://github.com/QuinteroAgustin/wattsup/blob/master/bdd/base.sql)

---

## 🛠️ Technologies utilisées

### **Frontend**

* Twig
* Tailwind CSS
* JS

### **Backend**

* Php
* Maria DB

### **Services Externes**

* Mercure
* MailTrap (Hebergeur de serveur de mail (SMTP))

## 🌟 Contribution

1. Clonez le dépôt : :
   ```bash
    git clone https://github.com/QuinteroAgustin/wattsup.git


## 🔗 Liens utiles
- [Symfony](https://symfony.com/download)
- [Scoop](https://scoop.sh/)
- [Composer](https://getcomposer.org/download/)
- [NodeJS](https://nodejs.org/en/download/package-manager/current)
