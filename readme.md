Assistant Domotique
=====================

##### En quelques mots

Assistant domotique est une application web de gestion Domotique s'installant sur un Raspberry pi. 
Pensée pour rendre votre maison intelligente, elle vous permettra de centraliser vos appareils multimédias, votre éclairage, votre alarme, pour un coût matériel dérisoire.

Adaptable très facilement, l'application s'intalle sur un raspberry pi en quelques minutes et se contrôle depuis l'adresse IP du serveur, branché à votre box.

Depuis votre smartphone, vous pourrez visualiser en un clin d'oeil les appareils éléctriques de votre maison, faire parler votre raspberry pi, jouer vos playlists, la radio, etc. 

L'assistant, une fois configuré veillera sur votre maison lorsque vous n'êtes pas là, contrôlera la température pour vous, vous réveillera le matin en vous informant de l'actualité et de la méteo. Il comprendra vos habitudes et saura vous aider dans la gestion de votre domotique.

Pensé pour les développeurs, l'assistant est très facile à maintenir et à faire évoluer. Vous pourrez en quelques lignes rajouter des fonctionnalités, des lampes, des appareils à contrôler.

##### Ses avantages

Les avantages principaux de cette solution Domotique en comparaison avec des solutions intégrées sont son coût, et sa rapidité de mise en place.
En effet, le coût total du matériel utilisé pour gérer les différentes fonctionnalités est inférieur à 60 euros, contre plus de 300 euros pour une box domotique beaucoup moins évolutive et complète.

##### Pour commencer

L'assistant requiert pour fonctionner parfaitement un Raspberry PI, en version B ou B+.
Afin de pousser encore plus loin l'intégration domotique et éléctronique, vous aurez certainement envie de faire l'acquisition d'une Arduino et de quelques capteurs (mouvement, température, humidité, etc) afin d'automatiser un certain nombre de tâches supplémentaires. Un paragraphe est dédié en fin de documentation sur l'intégration d'arduinos dans le processus.


Démo live de l'app
---------------

http://domotique.michaelvilleneuve.fr

Vidéo de l'app en fonctionnement terrain
----------------------------------------

https://youtu.be/kK4GbKNqGng


Fonctionnalités
---------------

Voici les différentes fonctionnalités de l'assistant ainsi que les besoins logiciels ou matériels :

### Contrôle des appareils électriques 

Grâce à des prises télécommandés. Vous pourrez allumer et éteindre vos prises, lumières, appareils électriques à distance. La fréquence utilisée par l'émetteur relié au raspberry Pi vous permettra de contrôler autre chose que des prises télécommandées. Avec un peu de volonté vous pourrez parvenir à contrôler n'importe quel appareil fonctionnant sur cette fréquence standard pour la domotique (portail télécommandé, garage, volets roulants, stations météo, etc).

- Requis : prises télécommandées (http://www.amazon.fr/dp/B00IVIK8XW/ref=sr_ph?ie=UTF8&qid=1427402808&sr=1&keywords=prises+télécommandées), émetteur 433mhz (http://www.amazon.fr/Neuftech®-433mhz-transmetteur-récepteur-Arduino/dp/B00NIBI7IK/ref=sr_1_1?ie=UTF8&qid=1427402900&sr=8-1&keywords=emetteur+433mhz) 

### Contrôle de la musique

Via l'interface proposée, vous pourrez contrôler la musique et utiliser de nombreuses radios. 7 stations sont integrées par défaut. (NRJ, FUN RADIO, NOVA, VIRGIN, RADIO CLASSIQUE, FRANCE MUSIQUE et FIP). Vous pourrez également contrôler le volume sonore. 
Lors d'un appui sur play, le lecteur intelligent ira automatiquement chercher dans le dossier "music" et lira aléatoirement les musiques qui s'y trouvent. 

Pour aller encore plus loin dans l'integration vous pouvez créer une borne airplay grâce à Shairport. 

- Requis : Des enceintes branchées sur le raspberry Pi, Alsamixer, MOCP, et une playlist si vous souhaitez jouer vos propres musiques.

### Contrôle du réveil

Vous pourrez créer votre propre réveil grâce au dossier crontab. Pour cela, définissez une tâche CRON ('crontab -e'), et paramétrez l'heure et la récurrence du réveil. Par défaut, l'assistant ne vous réveillera pas le week end.
Le scénario intégré est le suivant : L'assistante vous dit `Bonjour Monsieur, nous somme le $date, il est $heure, la température exterieure est de $degrés, le temps est $météo.`, l'assitante allume ensuite une lampe de votre choix et joue doucement Fip radio.

- Requis : Tâche Cron programmée

### Alerte SMS

 Si l'alarme est déclenchée, alerte en cas de température trop élevée, etc : L'assistant peut vous envoyer des SMS afin de vous avertir de différents scénarios (température trop élevée, alarme, rappel temporaire paiement des factures, etc).
Modifiez le fichier /smsenvoi/smsenvoi.config.php afin de paramétrer vos identifiants.

- Requis : Avoir un compte avec du crédit SMS sur SMSENVOI.com et récupérer sa clé API dans ses paramètres. 

### Assistant personnalisé

L'onglet assistant vous permet de donner des instructions écrite à l'assistant. Vous pourrez ainsi lui faire exécuter les ordres de votre choix. Exemple, écrivez 'Dis $laphraseadire', ou encore 'exec $unecommandeunix', etc. Pour rajouter des commandes, complétez le switch dans le fichier Gladys.php
Par défaut vous pourrez contrôler les lumières, la musique, lui faire dire des choses, et exécuter n'importe quelle commande unix, l'assistant vous renverra le contenu retourné par la commande.
Vous pourrez ainsi sans connexion SSH relancer votre serveur, installer d'autres paquets, etc. Attention à bien paramétrer votre serveur pour que les commandes fonctionnent.

- Requis : Rien par défaut, tout dépend de votre imagination et des commandes que vous souhaitez faire éxecuter.

### Statistiques d'utilisation

À chaque fois que vous déclenchez l'une des lampes ou un des éléments en page d'accueil, l'assistant comptabilise le nombre de changements afin de vous offrir des statistiques. À vous d'en faire l'utilisation souhaitée

- Requis : Rien

### Gestion du chauffage

La gestion du chauffage est intégrée par défaut si vous avez un chauffage éléctrique branché à vos prises télécommandées. Sinon vous devrez passer par des relais pour activer votre chauffage au moment voulu. Par défaut l'assistant déclenche le chauffage dès qu'il fait moins de 19 degrès. 

- Requis : Capteur de température pour la gestion automatisée, prise télécommandées, émetteur 433mhz

### Serveur Wake On Lan 

Allumage et extinction à distance d'un ordinateur : en configurant votre ordinateur fixe pour le Wake On Lan, vous pourrez réveiller et éteindre votre ordinateur à distance en renseignant son adresse mac et en installant un petit utilitaire sur votre pc.
Pour vérifier l'état de l'ordinateur vous devrez renseigner son adresse IP (elle doit donc être fixe).

- Requis : Un PC fixe secondaire, WakeOnlan

### Alarme de maison

Couplé à un capteur de mouvement, vous pourrez transformer votre raspberry pi en un système efficace d'alarme. Lorsque vous partez de chez vous, activez le verrouillage et la maison sera automatiquement mise sur alarme.
Si un mouvement est détécté, une alarme se déclenche et un SMS vous sera automatiquement envoyé pour vous prévenir.

- Requis : Capteur de mouvement, SMSENVOI



Fonctionnement technique 
========================

La configuration principale de l'assistant doit être faite dans le controlleur. Vous renseignerez dans les attributs votre configuration.

Front-end
---------

Le front-end est basé sur Framework7 http://www.idangero.us/framework7/. L'interface est celle d'IOS 7 et est optimisée pour iPhone.
Les icônes sont issues d'IOS7 via la police d'icône pe7 icons.

Le layout global est constitué par les fichiers Header.php et Footer.php.
Le système de vues se retrouve dans les fichiers ayant la terminaison '-view.php'.

Tous les différents onglets de l'application se retrouvent dans ces fichiers. 

L'ensemble des requêtes au serveur sont faites en AJAX. Le javascript de chaque vue se trouve à la fin des fichiers de vue. Vous pourrez ainsi facilement ajouter des éléments à controler en quelques lignes de code, en vous basant sur l'existant.

Back-end
--------

L'assistant est développé sur la base d'une architecture MVC. N'ayant pas par défaut de base de données, le Model est remplacé par des fichiers servant de stockage, les requêtes vers les fichiers sont effectuées dans le controlleur. 

L'ensemble des requêtes AJAX atteignent la méthode Ajax. L'action à effectuée est déterminée par le SWITCH action. 
Afin d'ajouter des inputs à administrer sur le front, vous n'avez ainsi qu'à rajouter un cas dans le switch. La requête appellée via la fonction post() dans la vue sera traitée à cet endroit. 

Par défaut 1 seul controlleur est mis en place. En fonction de vos besoins vous préférerez certainement rajouter des controlleurs afin de séparer les fonctionnalités de l'assistant.

Les routes peuvent être administrées via le routeur.

L'API d'envoi de SMS peut-être activée en renseignant vos clé API dans le fichiers /smsenvoi/smsenvoi.config.php. Vous devez vous créer un compte sur smsenvoi.com.

Les fichiers *.txt servent à stocker les données des capteurs, mais également à stocker la configuration (gestion automatisée du chauffage, du réveil, etc.).


Installation
============

Les prérequis de l'installation sont les suivants : 

	# APACHE 2	
	sudo apt-get install apache2
	
	# PHP5
	sudo apt-get install php5 libapache2-mod-php5
	
	# CURL
	sudo apt-get install curl
	sudo apt-get install php5-curl
	
	# Ajoutez WWW-DATA aux sudoers

	# ALSAMIXER Gestion du son (volume)
	sudo apt-get install alsa-utils
	
	# MPG123, Utilisé pour lire les MP3 retournés par Google ainsi que pour la Radio
	sudo apt-get install mpg123
	
	# MOCP, Gestion du lecteur média
	sudo apt-get update && sudo apt-get install moc moc-ffmpeg-plugin
	
	# WiringPi, gestion des GPIOS
	git clone git://git.drogon.net/wiringPi
	
	# RCSwitch, gestion d'un kit émetteur récepteur 433,92mhz
	git clone https://github.com/r10r/rcswitch-pi

	# Wake On Lan, réveil d'un PC à distance
	sudo apt-get install wakeonlan

	# Obtenez une IP fixe, soit via votre box, soit directement sur le raspberry pi

Une fois ceci-fait, deux solutions s'offrent à vous pour télécharger l'assistant vers votre serveur.

### En SSH


	# Placez-vous dans le dossier racine du serveur Apache (par défaut /var/www
	cd /var/www 

	# installez Git si ce n'est pas déjà fait 
	sudo apt-get install git

	# Clonez ce repository
	git clone https://github.com/Michaelvilleneuve/domotique-assistant

### Via FTP ou ce que vous voulez

Téléchargez le zip et placez les fichiers à la racine de votre serveur web.

Accéder à l'administration depuis l'éxterieur
---------------------------------------------

Pour accéder à l'administration de votre domotique depuis l'exterieur, vous devez ouvrir le port 80 de votre box et le diriger vers l'adresse IP du serveur.
Ensuite, tapez dans votre navigateur l'adresse IP publique de votre box et vous accéderez à votre assistant.

### Précaution sécurité

L'utilisation de l'assistant demande d'offrir à l'utilisateur WWW-DATA des privilèges élevés sur votre serveur. Faite donc attention à bien sécuriser votre réseau ainsi que votre serveur.

La configuration de base ne requiert pas d'authentification. Il est essentiel de mettre en place un tel système afin de restreindre l'accès à l'application.

Utilisation comme application Smartphone
----------------------------------------

Ouvrez la page web et faites "ajouter à l'écran d'accueil", vous la retrouverez sous forme d'application.

Améliorer votre assistant
-------------------------

Vous vous en doutez, par défaut l'assistant permet de gérer des cas simple d'utilisation avec simplement un raspberry pi. 
Pour plus d'intégration éléctronique, et donc de scénarios, vous pouvez facilement ajouter une ou plusieurs Arduino et les faire intéragir avec votre système.

### Ajouter une Arduino

Afin d'étendre les capacités de notre assistant, vous pouvez ajouter une arduino, qui sera utiliser pour toutes les actions électroniques. 

Le Raspberry PI sera ainsi dédié aux processus de haut niveau (gestion du serveur web, requêtes avec les API externes, gestion de la musique, réveil, etc).

L'Arduino, branchée avec un Ethernet Shield par exemple, pourra de son côté effectuer des requêtes pour envoyer des informations au Raspberry PI. 
À travers ces requêtes, vous pourrez communiquer l'état de différents capteurs par exemple. Cela servira pour la gestion de l'alarme de maison, pour la température, l'écoute de signaux 433mhz, etc.

Voici un exemple de requête HTTP pouvant être envoyée par une Arduino et comprise par le raspberry pi : 


	void envoyerrequete(String page, String action, int val) {
	  DHT.read11(dht_dpin);
	  float tempe = DHT.temperature;
	  float hum = DHT.humidity;
	  client.stop();
	  if (client.connect(server, 80)) {
	    Serial.println("connexion...");
	    client.println("GET /"+ page +".php?auto=a&q=ajax&action="+ action+"&temp="+tempe+"&hum="+hum+"&val="+val+" HTTP/1.1");
	    client.println("Host: 192.168.X.X");
	    client.println("User-Agent: arduino-ethernet");
	    client.println("Connection: close");
	    client.println();
	  }
	  else {
	    Serial.println("Echec connexion");
	  }
	}


Cette fonction prend en paramètre la page à questionner (en général index), l'action à effectuer (le case du switch à activer), et dans le cas des prises télécommandées, la valeur que l'on souhaite envoyer (0 pour OFF, 1 pour ON).

























