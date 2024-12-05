# 🚲 WattUp

## 🚀 Introduction

**WattUp** est une application de gestion de tournées pour les vélos électriques dédiés à la collecte des déchets. Elle combine une interface utilisateur intuitive (Frontend) avec une API robuste (Backend), et utilise [OpenRouteService (ORS)](https://openrouteservice.org/) pour calculer les itinéraires optimaux.

---

## 🛠️ IDE & Outils recommandés

### **🖥️ IDE**
- [Visual Studio Code (VSCode)](https://code.visualstudio.com/) - pour un développement rapide et efficace.

### **🐋 Docker**
- [Docker](https://www.docker.com/) - pour le déploiement et la gestion de l'environnement d'exécution de l'ORS.

---

## 📂 Structure du Projet

WattUp
* ├── templates/ # Interface utilisateur (twig) 
* ├── src/ # API et logique métier (php) 

---

## 🌐 Application

### **Installation et Exécution**

1. Rendez-vous dans le dossier **symfony** :
   ```bash
   https://symfony.com/download

2. Installez les dépendances :
   ```bash
   Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
   Invoke-RestMethod -Uri https://get.scoop.sh | Invoke-Expression
   scoop install symfony-cli

3. Lancez le serveur de développement :
   ```bash
   npm run dev

---

## 🖧 Backend

### **Installation et Exécution**

1. Rendez-vous dans le dossier **backend** :
   ```bash
   cd backend

2. Installez les dépendances :
   ```bash
   npm install

3. Lancez le serveur de développement :
   ```bash
   npm run dev

---

## 🗺️ OpenRouteService (ORS)

### **Installation et Exécution**

1. Rendez-vous dans le dossier **ors** :
   ```bash
   cd ors

2. Lancez le service Docker pour ORS :
   ```bash
   docker compose up

3. Accédez à l'interface ORS à l'adresse suivante : http://localhost:8080

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
* Mail Trap (Hebergeur de serveur de mail (SMTP))

## 🌟 Contribution

1. Clonez le dépôt : :
   ```bash
    git clone https://github.com/QuinteroAgustin/wattsup.git


## 🔗 Liens utiles
- [Symfony](https://symfony.com/download)
- [Scoop](https://scoop.sh/)
- [Composer](https://getcomposer.org/download/)
- [NodeJS](https://nodejs.org/en/download/package-manager/current)
