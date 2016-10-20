<?php
class Mail
{

    private $_mail_expediteur;
    private $_nom_expediteur;
    private $_mail_destinataire;
    private $_mail_replyto;
    private $_bcc;
    private $_objet; //sujet du mail
    private $_text; // Message du texte brut
    private $_html; // Message formaté en HTML
    private $_fichiers; // Pièces jointes
    private $_message; // Contenu global du mail
    private $_frontiere; // frontière de séparation des contenus
    private $_headers; // Header du mail

    private static function _validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function __construct ($mail_expediteur, $nom_expediteur, $mail_replyto)
    {
        if (!self::_validateEmail($mail_expediteur))
        {
            throw new InvalidArgumentException("Mail expéditeur invalide !");
        }
        if (!self::_validateEmail($mail_replyto))
        {
            throw new InvalidArgumentException("Mail reply to invalide ! ");
        }

        // Initialiser les propriétés
        $this->_mail_expediteur = $mail_expediteur;
        $this->_nom_expediteur = $nom_expediteur;
        $this->_mail_destinataire = '';
        $this->_mail_replyto = $mail_replyto;
        $this->_bcc = '';
        $this->_objet = '';
        $this->_text = '';
        $this->_html = '';
        $this->_fichiers = '';
        $this->_message = '';
        $this->_frontiere = md5(uniqid(mt_rand()));
        $this->_headers = '';
        add_action('init', array($this,'test'));
  }

  public function test(){
    var_dump('tete');exit;
  }

    public function ajouter_destinataire($mail)
    {
        if (!self::_validateEmail($mail))
        {
            throw new InvalidArgumentException ("Mail destinataire invalide !");
        }
        if ($this->_mail_destinataire == '')
        {
            $this->_mail_destinataire = $mail;
        }
        else {
            $this->_mail_destinataire .= ';'.$mail;
        }
    }

    public function ajouter_bcc($bcc)
    {
        if (!self::_validateEmail($bcc))
        {
            throw new InvalidArgumentException ("Mail destinataire invalide !");
        }
        if ($this->_bcc == '')
        {
            $this->_bcc= $bcc;
        }
        else {
            $this->_bcc .= ';'.$bcc;
        }
    }

    public function ajouter_pj($fichiers)
    {
        if (!file_exists($fichiers))
        {
            throw new InvalidArgumentException ('Pièce jointe inéxistante !');
        }
        if ($this->_fichiers == '')
        {
            $this->_fichiers = $fichiers;
        }
        else {
            $this->fichiers .= ';'.$fichiers;
        }
    }

    public function contenu($objet, $text, $html)
    {
        $this->_objet = $objet;
        $this->_text = $text;
        $this->_html = $html;
    }

    public function envoyer(){
        //Header du mail
        $this->_headers = 'From: "' . $this->_nom_expediteur . '" <' . $this->_mail_expediteur . '>' . "\n";
        $this->_headers .= 'Return-Path: <' . $this->_mail_replyto . '>' . "\n";
        $this->_headers .= 'MIME-Version: 1.0' . "\n";

        if ($this->_bcc != '') {
            $this->_headers .= "Bcc : " . $this->_bcc . "\n";
        }
        $this->_headers .= 'Content-Type : multipart/mixed; boundary="' . $this->_frontiere . '"';

        //Partie texte brut
        if ($this->_text != '') {
            $this->_message = '--' . $this->_frontiere . "\n";
            $this->_message .= 'Content-Type : text/html; charset="utf-8"' . "\n";
            $this->_message .= 'Content-Transfer-Encoding: 8bit' . "\n\n";
            $this->_message .= $this->_text . "\n\n";
        }
        //Partie html du mail
        if ($this->_html != '') {
            $this->_message = '--' . $this->_frontiere . "\n";
            $this->_message .= 'Content-Type : text/html; charset="utf-8"' . "\n";
            $this->_message .= 'Content-Transfer-Encoding: 8bit' . "\n\n";
            $this->_message .= $this->_html . "\n\n";
        }

        //partie pièce jointe
        if ($this->_fichiers != '') {
            $tab_fichiers = explode(';', $this->_fichiers);
            $nb_fichiers = count($tab_fichiers);

            for ($i = 0; $i < $nb_fichiers; $i++) {
                $this->_message .= '--' . $this -> _frontiere . "\n";;
                $this->_message .= 'Content-Type: image/jpeg; name=" ' . $tab_fichiers[$i] . ' " ' . "\n";
                $this->_message .= 'Content-Transfer-Encoding: base64' . "\n";
                $this->_message .= 'Content-Disposition:attachement; filename=" ' . $tab_fichiers[$i] . ' " ' . "\n\n";
                $this->_message .= chunk_split(base64_encode(file_get_contents($tab_fichiers[$i]))) . "\n\n";
            }
        }
        // Envoi du mail

        if(!wp_mail($this -> _mail_destinataire, $this -> _objet, $this -> _message, $this -> _headers)){
            throw new Exception('Envoi du mail échoué!');
        }

    } // fonction envoyer
}
