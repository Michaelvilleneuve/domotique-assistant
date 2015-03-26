Assistant Domotique
=====================

L'assistant domotique fonctionne sur un serveur Apache2 avec PHP5.
Le tout est prévu pour fonctionner sur un raspberry PI sous Raspbian mais fonctionnera sur n'importe quel appareil sous Debian, et avec quelques adaptations sur d'autres distributions Linux.
L'ensemble ne nécessite pas de base de données dans la mesure ou les données sont stockées dans les fichiers *.txt.

Les prérequis de l'installation sont les suivants : 
	* APACHE 2 

	sudo apt-get install apache2
	
	* PHP5 --> sudo apt-get install php5 libapache2-mod-php5
	* CURL --> sudo apt-get php5-curl
	* WWW-DATA doit avoir les privilèges root (pour les commandes unix avec 'exec') --> attention aux permissions que vous offrez à l'utilisateur WWW-DATA selon votre configuration réseau
	* ALSAMIXER --> Gestion du son (volume) "sudo apt-get install alsa-utils"
	* MPG123 --> Utilisé pour lire les MP3 retournés par Google ainsi que pour la Radio "sudo apt-get install mpg123"
	* MOCP --> Gestion du lecteur média "sudo apt-get update && sudo apt-get install moc moc-ffmpeg-plugin"
	* WiringPi --> git clone git://git.drogon.net/wiringPi
	* RCSwitch --> git clone https://github.com/r10r/rcswitch-pi
	* Wake On Lan --> sudo apt-get install wakeonlan

Fonctionnalités
---------------

Voici les différentes fonctionnalités de l'assistant ainsi que les besoins logiciels ou matériels :

	- Contrôle des appareils électriques : Grâce à des prises télécommandés. Vous pourrez allumer et éteindre vos prises, lumières, appareils électriques à distance. La fréquence utilisée par l'émetteur relié au raspberry Pi vous permettra de contrôler autre chose que des prises télécommandées. Avec un peu de volonté vous pourrez parvenir à contrôler n'importe quel appareil fonctionnant sur cette fréquence standard pour la domotique (portail télécommandé, garage, volets roulants, stations météo, etc).

	Requis :  - prises télécommandées (http://www.amazon.fr/dp/B00IVIK8XW/ref=sr_ph?ie=UTF8&qid=1427402808&sr=1&keywords=prises+télécommandées)
	- Émetteur 433mhz (433mhz http://www.amazon.fr/Neuftech®-433mhz-transmetteur-récepteur-Arduino/dp/B00NIBI7IK/ref=sr_1_1?ie=UTF8&qid=1427402900&sr=8-1&keywords=emetteur+433mhz)

	- Contrôle de la musique : Via l'interface proposée, vous pourrez contrôler la musique et utiliser de nombreuses radios. 7 stations sont integrées par défaut. (NRJ, FUN RADIO, NOVA, VIRGIN, RADIO CLASSIQUE, FRANCE MUSIQUE et FIP). Vous pourrez également contrôler le volume sonore. 
	Lors d'un appui sur play, le lecteur intelligent ira automatiquement chercher dans le dossier "music" et lira aléatoirement les musiques qui s'y trouvent. 

	Pour aller encore plus loin dans l'integration vous pouvez créer une borne airplay grâce à Shairport. 

	Requis : Des enceintes branchées sur le raspberry Pi, Alsamixer, MOCP, et une playlist si vous souhaitez jouer vos propres musiques.

	- Contrôle du réveil : Vous pourrez créer votre propre réveil grâce au dossier crontab. Pour cela, définissez une tâche CRON ('crontab -e'), et paramétrez l'heure et la récurrence du réveil. Par défaut, l'assistant ne vous réveillera pas le week end.
	Le scénario intégré est le suivant : L'assistante vous dit "Bonjour Monsieur, nous somme le $date, il est $heure, la température exterieure est de $degrés, le temps est $météo.", l'assitante allume ensuite une lampe de votre choix et joue doucement Fip radio.

	Requis : Tâche Cron programmée

	- Alerte SMS si l'alarme est déclenchée, alerte en cas de température trop élevée, etc : L'assistant peut vous envoyer des SMS afin de vous avertir de différents scénarios (température trop élevée, alarme, rappel temporaire paiement des factures, etc).
	Modifiez le fichier /smsenvoi/smsenvoi.config.php afin de paramétrer vos identifiants.

	Requis : Avoir un compte avec du crédit SMS sur SMSENVOI.com et récupérer sa clé API dans ses paramètres. 

	- Assistant personnalisé : L'onglet assistant vous permet de donner des instructions écrite à l'assistant. Vous pourrez ainsi lui faire exécuter les ordres de votre choix. Exemple, écrivez 'Dis $laphraseadire', ou encore 'exec $unecommandeunix', etc. Pour rajouter des commandes, complétez le switch dans le fichier Gladys.php
	Par défaut vous pourrez contrôler les lumières, la musique, lui faire dire des choses, et exécuter n'importe quelle commande unix, l'assistant vous renverra le contenu retourné par la commande.
	Vous pourrez ainsi sans connexion SSH relancer votre serveur, installer d'autres paquets, etc. Attention à bien paramétrer votre serveur pour que les commandes fonctionnent.

	Requis : Rien par défaut, tout dépend de votre imagination et des commandes que vous souhaitez faire éxecuter.

	- Statistiques d'utilisation : À chaque fois que vous déclenchez l'une des lampes ou un des éléments en page d'accueil, l'assistant comptabilise le nombre de changements afin de vous offrir des statistiques. À vous d'en faire l'utilisation souhaitée

	Requis : Rien

	- Gestion du chauffage : La gestion du chauffage est intégrée par défaut si vous avez un chauffage éléctrique branché à vos prises télécommandées. Sinon vous devrez passer par des relais pour activer votre chauffage au moment voulu. Par défaut l'assistant déclenche le chauffage dès qu'il fait moins de 19 degrès. 

	Requis : Capteur de température pour la gestion automatisée, prise télécommandées, émetteur 433mhz

- Serveur Wake On Lan (Allumage et extinction à distance d'un ordinateur) : En configurant votre ordinateur fixe pour le Wake On Lan, vous pourrez réveiller et éteindre votre ordinateur à distance en renseignant son adresse mac.
Pour vérifier l'état de l'ordinateur vous devrez renseigner son adresse IP (elle doit donc être fixe).

Requis : Un PC fixe secondaire, WakeOnlan

- Alarme de maison : Couplé à un capteur de mouvement, vous pourrez transformer votre raspberry pi en un système efficace d'alarme. Lorsque vous partez de chez vous, activez le verrouillage et la maison sera automatiquement mise sur alarme.
Si un mouvement est détécté, une alarme se déclenche et un SMS vous sera automatiquement envoyé.

Requis : Capteur de mouvement, SMSENVOI



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

L'ensemble des requêtes sont faites en AJAX. Le javascript de chaque vue se trouve à la fin des fichiers de vue. Vous pourrez ainsi facilement ajouter des éléments à controler en quelques lignes de code, en vous basant sur l'existant.

Back-end
--------

L'assistant est développé sur la base d'une architecture MVC. N'ayant pas souhaité pour l'instant intégrer de base de données, le Model est remplacé par des fichiers servant de stockage, les requêtes vers les fichiers sont effectuées dans le controlleur. 

L'ensemble des requêtes AJAX atteignent la méthode Ajax. L'action à effectuée est déterminée par le SWITCH action. 
Afin d'ajouter des inputs à administrer sur le front, vous n'avez ainsi qu'à rajouter un cas dans le switch. La requête appellée via la fonction post() dans la vue sera traitée à cet endroit. 

Par défaut 1 seul controlleur est mis en place. En fonction de vos besoins vous préférerez certainement rajouter des controlleurs afin de séparer les fonctionnalités de l'assistant.

Les routes peuvent être administrées via le routeur.

L'API d'envoi de SMS peut-être activée en renseignant vos clé API dans le fichiers /smsenvoi/smsenvoi.config.php

Les fichiers *.txt servent à stocker les données des capteurs, mais également à stocker la configuration (gestion automatisée du chauffage, du réveil, etc.).










































