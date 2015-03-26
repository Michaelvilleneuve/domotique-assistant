<?php

/*
* Librairie d'envoi SMS, SMS ENVOI.
* Cette librairie nécessite PHP 5 ainsi que la librairie cURL 
* N'oubliez pas d'éditer le fichier smsenvoi.config.php avant de tenter de l'utiliser
* L'utilisation de cette librairie nécessite une inscription (gratuite) préalable sur le site http://www.smsenvoi.com ainsi que des crédits SMS.
* @package pagepackage
*/
require_once("smsenvoi.config.php");



/*
* Classe SMSENVOI à instancier et utiliser pour communiquer avec le serveur SMSENVOI.com
* @package smsenvoi
* @author Stephane Nachez <s.nachez@smsenvoi.com>
* @copyright SMSENVOI.com
*/
class smsenvoi{

	/**
	* Résultat Brut
	* @access public
	* @var object
	*/
	public $rawresult;
	
	/**
	* Résultat
	* @access public
	* @var object
	*/
	public $result;
	
	/**
	* Indique si le dernier SMS a bien été envoyé
	* @access public
	* @var boolean
	*/
	public $success;
	
	/**
	* Vous permet de passer la librairie en mode Debug pour afficher les messages d'information utiles à sa mise en oeuvre
	* @access public
	* @var boolean
	*/
	public $debug=false;
	
	/**
	* Id du dernier message correctement envoyé
	* @access public
	* @var integer
	*/
	public $id;

	public function __construct(){
		
		
		if(SMSENVOI_EMAIL==''){die('Vous devez configurer le fichier smsenvoi.config.php'); }
	}

	/**
	* Requête cURL - Vous n'êtes pas sensé appeler cette méthode
	* @access private
	* 
	*/
	private function _postRequest($url,$fields){
		
		
		$fields['email']=SMSENVOI_EMAIL;
		$fields['apikey']=SMSENVOI_APIKEY;
		$fields['version']=SMSENVOI_VERSION;
		
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_URL,$url);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_POSTFIELDS,http_build_query($fields));
		$result = curl_exec ($curl);
		
		curl_close ($curl);
		
		return $result;
		
	}
	
	
	/**
	* Fonction d'envoi de SMS pour votre compte principal
	* @param string $recipients liste des numéros destinataires séparés par une virgule
	* @param string $content contenu du message
	* @param string $subtype Gamme utilisée pour l'envoi ( LOWCOST / STANDARD / PREMIUM / STOP / LONG ) paramètre optionnel, par défaut : PREMIUM
	* @param string $senderlabel Nom d'expéditeur à afficher pour les gammes le permettant
	* @param string $senddate Date d'envoi au format "YYYY-mm-dd" , si les paramètres $senddate et $sendtime ne sont pas remplis l'envoi sera instantané
	* @param string $sendtime Heure d'envoi au format "HH:mm:ss" , si les paramètres $senddate et $sendtime ne sont pas remplis l'envoi sera instantané
	* @param integer $richsms_option Option rich SMS à utiliser: 1 ou 2
	* @param string $richsms_url Adresse URL à raccourcir
	*/
	public function sendSMS($recipients,$content,$subtype='PREMIUM',$senderlabel='SMS ENVOI',$senddate='',$sendtime='',$richsms_option='',$richsms_url=''){
		
		
		if(substr_count($recipients,',')>1000){  if($this->debug){ echo "1000 destinataires maximum par requête";}  return false; }
		$fields['message']['recipients']=$recipients;
		$fields['message']['content']=$content;
		$fields['message']['subtype']=$subtype;
		$fields['message']['senderlabel']=$senderlabel;
		
		if($senddate!=''){ $fields['message']['senddate']=$senddate;}
		if($sendtime!=''){ $fields['message']['sendtime']=$sendtime;}
		if(($richsms_option!='')&&($richsms_url!='')){
			$fields['shorturl']['option']=$richsms_option;
			$fields['shorturl']['url']=$richsms_url;
		}
		
		$this->rawresult= $this->_postRequest("http://www.smsenvoi.com/httpapi/sendsms/",$fields);
		
		
		$this->result=json_decode($this->rawresult);
		if($this->debug && (isset($this->result->message))){ echo $this->result->message; }	

		
		if(isset($this->result->success)&&($this->result->success==1)){ $this->success=true; $this->id=$this->result->message_id; }else{ $this->success=false;}
		return $this->success;
		
	}
	
	
	/**
	* Fonction d'Appel vocal pour votre compte principal
	* @param string $recipients liste des numéros destinataires séparés par une virgule
	* @param string $content contenu du message
	* @param string $senddate Date d'envoi au format "YYYY-mm-dd" , si les paramètres $senddate et $sendtime ne sont pas remplis l'envoi sera instantané
	* @param string $sendtime Heure d'envoi au format "HH:mm:ss" , si les paramètres $senddate et $sendtime ne sont pas remplis l'envoi sera instantané
	*/
	public function sendCALL($recipients,$content,$senddate='',$sendtime=''){
		
		
		if(substr_count($recipients,',')>1000){  if($this->debug){ echo "1000 destinataires maximum par requête";}  return false; }
		$fields['message']['recipients']=$recipients;
		$fields['message']['content']=$content;
	
		if($senddate!=''){ $fields['message']['senddate']=$senddate;}
		if($sendtime!=''){ $fields['message']['sendtime']=$sendtime;}
		
		$this->rawresult= $this->_postRequest("http://www.smsenvoi.com/httpapi/sendcall/",$fields);
		
		
		$this->result=json_decode($this->rawresult);
		if($this->debug && (isset($this->result->message))){ echo $this->result->message; }	

		
		if(isset($this->result->success)&&($this->result->success==1)){ $this->success=true; $this->id=$this->result->message_id; }else{ $this->success=false;}
		return $this->success;
		
	}
	
	
	/**
	* Fonction d'envoi de SMS pour les comptes marque blanche
	* @param integer $cobrandingmember_id ID du compte marque blanche effectuant l'envoi
	* @param string $recipients liste des numéros destinataires séparés par une virgule
	* @param string $content contenu du message
	* @param string $subtype Gamme utilisée pour l'envoi ( LOWCOST / STANDARD / PREMIUM / STOP / LONG ) paramètre optionnel, par défaut : PREMIUM
	* @param string $senderlabel Nom d'expéditeur à afficher pour les gammes le permettant
	* @param string $senddate Date d'envoi au format "YYYY-mm-dd" , si les paramètres $senddate et $sendtime ne sont pas remplis l'envoi sera instantané
	* @param string $sendtime Heure d'envoi au format "HH:mm:ss" , si les paramètres $senddate et $sendtime ne sont pas remplis l'envoi sera instantané
	*/
	public function sendCobrandingSMS($cobrandingmember_id,$recipients,$content,$subtype='PREMIUM',$senderlabel='SMS ENVOI',$senddate='',$sendtime=''){
		
		if(substr_count($recipients,',')>1000){  if($this->debug){ echo "1000 destinataires maximum par requête";}  return false; }
		$fields['cobrandingmember_id']=$cobrandingmember_id;
		$fields['message']['recipients']=$recipients;
		$fields['message']['content']=$content;
		$fields['message']['subtype']=$subtype;
		$fields['message']['senderlabel']=$senderlabel;
		
		if($senddate!=''){ $fields['message']['senddate']=$senddate;}
		if($sendtime!=''){ $fields['message']['sendtime']=$sendtime;}
		
		$this->rawresult= $this->_postRequest("http://www.smsenvoi.com/httpapi/sendsms/",$fields);
		
		
		$this->result=json_decode($this->rawresult);
		if($this->debug && (isset($this->result->message))){ echo $this->result->message; }	

		
		if(isset($this->result->success)&&($this->result->success==1)){ $this->success=true; $this->id=$this->result->message_id; }else{ $this->success=false;
			
			
			if($this->debug){ echo $this->result->message; }
		}
		return $this->success;
		
	}
	
	
	/**
	* Fonction permettant de vérifier le statut (Accusé de réception) d'un envoi
	* @param integer $message_id ID du message concerné
	*/	 
	public function checkDelivery($message_id){
		
		
		$fields['message_id']=$message_id;
		$this->rawresult= $this->_postRequest("http://www.smsenvoi.com/httpapi/checkdelivery/",$fields);
		$this->result=json_decode($this->rawresult);
		if($this->debug && (isset($this->result->message))){ echo $this->result->message; }	

		return $this->result->listing;
		
		
		
	}
	
	/**
	* Fonction permettant d'obtenir la liste des stops
	* param @integer $cobrandingmember_id Id Sous compte marque blanche (facultatif)
	* @return array
	*/		
	public function checkStops($cobrandingmember_id=0){
	$fields=array();
		if($cobrandingmember_id!=0){$fields['cobrandingmember_id']=$cobrandingmember_id;}
		
		$this->rawresult= $this->_postRequest("http://www.smsenvoi.com/httpapi/checkstops/",$fields);

		$this->result=json_decode($this->rawresult);
		if($this->debug && (isset($this->result->message))){ echo $this->result->message; }	

	
		return $this->result->stops;
	
	}

	/**
	* Fonction permettant d'ajouter un stop
	* param @string $phone numéro de téléphone
	* param @integer $cobrandingmember_id Id Sous compte marque blanche (facultatif)
	* @return array
	*/		
	public function addStop($phone='',$cobrandingmember_id=0){
		$fields=array('phone'=>$phone);
		if($cobrandingmember_id!=0){$fields['cobrandingmember_id']=$cobrandingmember_id;}
		$this->rawresult= $this->_postRequest("http://www.smsenvoi.com/httpapi/addstop/",$fields);

		$this->result=json_decode($this->rawresult);
		if($this->debug && (isset($this->result->message))){ echo $this->result->message; }	
	
		return $this->result->success;
	
	
	
	}
	/**
	* Fonction permettant de vérifier le nombre de crédits restants sur votre compte principal
	* @return array
	*/	 	
	public function checkCredits(){
		$fields=array();
		$this->rawresult= $this->_postRequest("http://www.smsenvoi.com/httpapi/checkcredits/",$fields);
		$this->result=json_decode($this->rawresult);
		if($this->debug && (isset($this->result->message))){ echo $this->result->message; }	

		$return=array('call'=>array('PHONE'=>0,'MOBILEPHONE'=>0),'sms'=>array('LOWCOST'=>0,'STANDARD'=>0,'PREMIUM'=>0,'LONG'=>0,'STOP'=>0));
		if($this->result->success==1){
			
			$tempcreditsremaining=(array)$this->result->creditsremaining;
			
			
			if(isset($tempcreditsremaining["sms"])){
				
				foreach($tempcreditsremaining["sms"] as $k=>$v){
					switch($k){
						
					case 1: $return["sms"]["LOWCOST"]=$v; break;
					case 2: $return["sms"]["STANDARD"]=$v; break;
					case 3: $return["sms"]["PREMIUM"]=$v; break;
					case 4: $return["sms"]["LONG"]=$v; break;
					case 7: $return["sms"]["STOP"]=$v; break;
						
						
						
						
					}
					
					
				}
				
				
			}
			
			if(isset($tempcreditsremaining["call"])){
				
				foreach($tempcreditsremaining["call"] as $k=>$v){
					switch($k){
						
					case 1: $return["call"]["PHONE"]=$v; break;
					case 2: $return["call"]["MOBILEPHONE"]=$v; break;
						
						
						
						
					}
					
					
				}
				
				
			}
				return $return;
				
			
			
			
		}else{return false;}	
	}
	
	/**
	* Fonction permettant de créer un compte marque blanche ( Nécessite de créer au préalable une marque blanche SMSENVOI, 100% gratuit)
	* Pensez à récupérer et stocker l'Id retourné lors de la création de ce compte pour les différentes manipulations en rapport avec ce compte (envoi de sms, édition du compte..)
	* @param string $email adresse e-mail de connexion du compte client marque blanche
	* @param string $motdepasse mot de passe du compte client marque blanche
	* @param string $numero_portable (optionnel) numéro de téléphone du popriétaire du compte client marque blanche
	* @param string $nom (optionnel) nom du propriétaire du compte client marque blanche
	* @param string $prenom (optionnel) prénom du propriétaire du compte client marque blanche
	* @param string $raison_sociale (optionnel) raison sociale de la société propriétaire du compte client marque blanche
	*/	 
	public function createCobrandingMember($email,$motdepasse,$numero_portable='',$nom='',$prenom='',$raison_sociale=''){
		
		if($email==''||$motdepasse==''){ 
			if($this->debug){ echo "ECHEC Lors de la création du compte : L'e-mail et le mot de passe doivent être remplis";}
			return false;}
		$fields["cobrandingmember"]["email"]=$email;
		$fields["cobrandingmember"]["motdepasse"]=$motdepasse;
		if($numero_portable!=""){$fields["cobrandingmember"]["numero_portable"]=$numero_portable;}
		if($nom!=""){$fields["cobrandingmember"]["nom"]=$nom;}
		if($raison_sociale!=""){$fields["cobrandingmember"]["raison_sociale"]=$raison_sociale;}
		$this->rawresult= $this->_postRequest("http://www.smsenvoi.com/httpapi/createcobrandingmember/",$fields);
		$this->result=json_decode($this->rawresult);
		if($this->debug && (isset($this->result->message))){ echo $this->result->message; }	

		if($this->result->success!=1){return false;}
		return $this->result->cobrandingmember_id;
		
		
		
	}


	/**
	* Fonction permettant de créer un compte marque blanche ( Nécessite de créer au préalable une marque blanche SMSENVOI, 100% gratuit)
	* @param integer Id du compte client marque blanche
	* @param string $email adresse e-mail de connexion du compte client marque blanche
	* @param string $motdepasse mot de passe du compte client marque blanche
	* @param string $numero_portable (optionnel) numéro de téléphone du popriétaire du compte client marque blanche
	* @param string $nom (optionnel) nom du propriétaire du compte client marque blanche
	* @param string $prenom (optionnel) prénom du propriétaire du compte client marque blanche
	* @param string $raison_sociale (optionnel) raison sociale de la société propriétaire du compte client marque blanche
	*/	 	
	public function editCobrandingMember($id,$email,$motdepasse,$numero_portable='',$nom='',$prenom='',$raison_sociale=''){
		
		if($email==''||$motdepasse==''||$id==''){ 
			if($this->debug){ echo "ECHEC Lors de la modification du compte : L'ID, l'e-mail et le mot de passe doivent être remplis";}
			return false;}
		$fields["cobrandingmember"]["id"]=$id;
		$fields["cobrandingmember"]["email"]=$email;
		$fields["cobrandingmember"]["motdepasse"]=$motdepasse;
		if($numero_portable!=""){$fields["cobrandingmember"]["numero_portable"]=$numero_portable;}
		if($nom!=""){$fields["cobrandingmember"]["nom"]=$nom;}
		if($raison_sociale!=""){$fields["cobrandingmember"]["raison_sociale"]=$raison_sociale;}
		$this->rawresult= $this->_postRequest("http://www.smsenvoi.com/httpapi/editcobrandingmember/",$fields);
		$this->result=json_decode($this->rawresult);
		if($this->debug && (isset($this->result->message))){ echo $this->result->message; }	

		if(!isset($this->result->success)||($this->result->success!=1)){return false;}
		return true;
		
		
		
	}



	/**
	* Fonction permettant de récupérer les informations d'un compte client marque blanche 
	* @param integer $id Id de connexion du compte client marque blanche
	*/	 
	public function checkCobrandingMemberInfos($id){
		
		if($id==''){
			if($this->debug){ echo "ECHEC, l'ID ne peut être vide";}
			return false;
		}
		
		$fields["cobrandingmember"]["id"]=$id;
		$this->rawresult= $this->_postRequest("http://www.smsenvoi.com/httpapi/checkcobrandingmemberinfos/",$fields);
		$this->result=json_decode($this->rawresult);
		if($this->debug && (isset($this->result->message))){ echo $this->result->message; }	
		$cobrandingmember= (array)$this->result->cobrandingmember;
		$return= (array)reset($cobrandingmember);
		
		foreach($return as $k=>$v){if(substr($k,0,7)=='credits'){unset($return[$k]);}}
		return $return;
		
	}
	

	/**
	* Fonction permettant de récupérer le nombre de crédits restants d'un compte client marque blanche 
	* @param integer $id Id de connexion du compte client marque blanche
	*/	 
	public function checkCobrandingMemberCredits($id){
		
		if($id==''){
			if($this->debug){ echo "ECHEC, l'ID ne peut être vide";}
			return false;
		}
		
		$fields["cobrandingmember"]["id"]=$id;
		$this->rawresult= $this->_postRequest("http://www.smsenvoi.com/httpapi/checkcobrandingmemberinfos/",$fields);
		$this->result=json_decode($this->rawresult);
		if($this->debug && (isset($this->result->message))){ echo $this->result->message; }	
		$cobrandingmember= (array)$this->result->cobrandingmember;
		$temp= (array)reset($cobrandingmember);
		

		if(isset($temp["credits_sms_lowcost"])){ $return["LOWCOST"]=$temp["credits_sms_lowcost"];}
		if(isset($temp["credits_sms_standard"])){ $return["STANDARD"]=$temp["credits_sms_standard"];}
		if(isset($temp["credits_sms_premium"])){ $return["PREMIUM"]=$temp["credits_sms_premium"];}
		if(isset($temp["credits_sms_long"])){ $return["LONG"]=$temp["credits_sms_long"];}
		if(isset($temp["credits_sms_stop"])){ $return["STOP"]=$temp["credits_sms_stop"];}
		
		return $return;
		
	}


	/**
	* Fonction permettant de créditer un compte client marque blanche (les crédits sont puisés dans les crédits de votre compte principal) 
	* @param integer $id Id de connexion du compte client marque blanche
	* @param string $subtype Gamme de SMS à créditer ( LOWCOST / STANDARD / PREMIUM / STOP / LONG )
	* @param integer $nb Nombre de sms à créditer
	*/	 
	public function updateCobrandingMemberCredits($id,$subtype,$nb){
		
		$fields["cobrandingmember"]["id"]=$id;
		$fields["credits"]["type"]="SMS";
		$fields["credits"]["subtype"]=$subtype;
		$fields["credits"]["nb"]=$nb;
		
		$this->rawresult= $this->_postRequest("http://www.smsenvoi.com/httpapi/cobrandingmembercredits/",$fields);
		$this->result=json_decode($this->rawresult);
		if($this->debug && (isset($this->result->message))){ echo $this->result->message; }	


		if($this->result->success==1){ return true;}else{
			
			return false;
		}
		
		
		
	}







}


	?>