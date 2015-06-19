<?
/**
* This class checks the availability of a domain and gets the whois data
* You can check domains of the following tld's
* (PLEASE CONTACT ME IF A WHOIS SERVER ISN'T RIGHT!)
* - ac
* - ac.cn
* - ac.jp
* - ac.uk
* - ad.jp
* - adm.br
* - adv.br
* - aero
* - ag
* - agr.br
* - ah.cn
* - al
* - am.br
* - arq.br
* - at
* - au
* - art.br
* - as
* - asn.au
* - ato.br
* - be
* - bg
* - bio.br
* - biz
* - bj.cn
* - bmd.br
* - br
* - ca
* - cc
* - cd
* - ch
* - cim.br
* - ck
* - cl
* - cn
* - cng.br
* - cnt.br
* - com
* - com.au
* - com.br
* - com.cn
* - com.eg
* - com.hk
* - com.mx
* - com.ru
* - com.tw
* - conf.au
* - co.jp
* - co.uk
* - cq.cn
* - csiro.au
* - cx
* - cz
* - de
* - dk
* - ecn.br
* - ee
* - edu
* - edu.au
* - edu.br
* - eg
* - es
* - esp.br
* - etc.br
* - eti.br
* - eun.eg
* - emu.id.au
* - eng.br
* - far.br
* - fi
* - fj
* - fj.cn
* - fm.br
* - fnd.br
* - fo
* - fot.br
* - fst.br
* - fr
* - g12.br
* - gd.cn
* - ge
* - ggf.br
* - gl
* - gr
* - gr.jp
* - gs
* - gs.cn
* - gov.au
* - gov.br
* - gov.cn
* - gov.hk
* - gob.mx
* - gs
* - gz.cn
* - gx.cn
* - he.cn
* - ha.cn
* - hb.cn
* - hi.cn
* - hl.cn
* - hn.cn
* - hm
* - hk
* - hk.cn
* - hu
* - id.au
* - ie
* - ind.br
* - imb.br
* - inf.br
* - info
* - info.au
* - it
* - idv.tw
* - int
* - is
* - il
* - jl.cn
* - jor.br
* - jp
* - js.cn
* - jx.cn
* - kr
* - la
* - lel.br
* - li
* - lk
* - ln.cn
* - lt
* - lu
* - lv
* - ltd.uk
* - mat.br
* - mc
* - med.br
* - mil
* - mil.br
* - mn
* - mo.cn
* - ms
* - mus.br
* - mx
* - name
* - ne.jp
* - net
* - net.au
* - net.br
* - net.cn
* - net.eg
* - net.hk
* - net.lu
* - net.mx
* - net.uk
* - net.ru
* - net.tw
* - nl
* - nm.cn
* - no
* - nom.br
* - not.br
* - ntr.br
* - nx.cn
* - nz
* - plc.uk
* - odo.br
* - oop.br
* - or.jp
* - org
* - org.au
* - org.br
* - org.cn
* - org.hk
* - org.lu
* - org.ru
* - org.tw
* - org.uk
* - pl
* - pp.ru
* - ppg.br
* - pro.br
* - psi.br
* - psc.br
* - pt
* - qh.cn
* - qsl.br
* - rec.br
* - ro
* - ru
* - sc.cn
* - sd.cn
* - se
* - sg
* - sh
* - sh.cn
* - si
* - sk
* - slg.br
* - sm
* - sn.cn
* - srv.br
* - st
* - sx.cn
* - tc
* - th
* - tj.cn
* - tmp.br
* - to
* - tr
* - trd.br
* - tur.br
* - tv // ! .tv domains are limited in requests of WHOIS information at the server whois.tv up to 20 requests
* - tv.br
* - tw
* - tw.cn
* - uk
* - va
* - vet.br
* - vg
* - wattle.id.au
* - ws
* - xj.cn
* - xz.cn
* - yn.cn
* - zlg.br
* - zj.cn
*
* @author    Sven Wagener <sven.wagener@intertribe.de>
* @copyright Intertribe Limited
* @include 	 Funktion:_include_
*/
class domain{
    var $domain="";
    var $servers="";
    
    /**
    * Constructor of class domain
    * @param string	$str_domainame    the full name of the domain
    * @desc Constructor of class domain
    */
    function domain($str_domainname){
        /*******************************
        * Initializing server variables
        * array(top level domain,whois_Server,not_found_string or MAX number of CHARS: MAXCHARS:n)
        **/
        $i=0;
        $this->servers[$i++]=array("ac","whois.nic.ac","No match");
        $this->servers[$i++]=array("ac.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("ac.jp","whois.nic.ad.jp","No match");
        $this->servers[$i++]=array("ac.uk","whois.ja.net","no entries");
        $this->servers[$i++]=array("ad.jp","whois.nic.ad.jp","No match");
        $this->servers[$i++]=array("adm.br","whois.nic.br","No match");
        $this->servers[$i++]=array("adv.br","whois.nic.br","No match");
        $this->servers[$i++]=array("aero","whois.information.aero","is available");
        $this->servers[$i++]=array("ag","whois.nic.ag","does not exist");
        $this->servers[$i++]=array("agr.br","whois.nic.br","No match");
        $this->servers[$i++]=array("ah.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("al","whois.ripe.net","No entries found");
        $this->servers[$i++]=array("am.br","whois.nic.br","No match");
        $this->servers[$i++]=array("arq.br","whois.nic.br","No match");
        $this->servers[$i++]=array("at","whois.nic.at","nothing found");
        $this->servers[$i++]=array("au","whois.aunic.net","No Data Found");
        $this->servers[$i++]=array("art.br","whois.nic.br","No match");
        $this->servers[$i++]=array("as","whois.nic.as","Domain Not Found");
        $this->servers[$i++]=array("asn.au","whois.aunic.net","No Data Found");
        $this->servers[$i++]=array("ato.br","whois.nic.br","No match");
        $this->servers[$i++]=array("be","whois.dns.be","No such domain");
        $this->servers[$i++]=array("bg","whois.digsys.bg","does not exist");
        $this->servers[$i++]=array("bio.br","whois.nic.br","No match");
        $this->servers[$i++]=array("biz","whois.biz","Not found");
        $this->servers[$i++]=array("bj.cn","whois.cnnic.net.cn","No entries found"); 
        // $this->servers[$i++]=array("bm","rwhois.ibl.bm","");
        $this->servers[$i++]=array("bmd.br","whois.nic.br","No match");
        $this->servers[$i++]=array("br","whois.registro.br","No match");
        $this->servers[$i++]=array("ca","whois.cira.ca","Status: AVAIL");
        $this->servers[$i++]=array("cc","whois.nic.cc","No match");
        $this->servers[$i++]=array("cd","whois.cd","No match");
        $this->servers[$i++]=array("ch","whois.nic.ch","We do not have an entry");
        $this->servers[$i++]=array("cim.br","whois.nic.br","No match");
        $this->servers[$i++]=array("ck","whois.ck-nic.org.ck","No entries found");        
        $this->servers[$i++]=array("cl","whois.nic.cl","no existe");
        $this->servers[$i++]=array("cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("cng.br","whois.nic.br","No match");	 
        $this->servers[$i++]=array("cnt.br","whois.nic.br","No match");	      
        $this->servers[$i++]=array("com","whois.verisign-grs.net","No match");
        $this->servers[$i++]=array("com.au","whois.aunic.net","No Data Found");
        $this->servers[$i++]=array("com.br","whois.nic.br","No match");
        $this->servers[$i++]=array("com.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("com.eg","whois.ripe.net","No entries found");
        $this->servers[$i++]=array("com.hk","whois.hknic.net.hk","No Match for");   
        $this->servers[$i++]=array("com.mx","whois.nic.mx","Nombre del Dominio");
        $this->servers[$i++]=array("com.ru","whois.ripn.ru","No entries found");
        $this->servers[$i++]=array("com.tw","whois.twnic.net","NO MATCH TIP");
        $this->servers[$i++]=array("conf.au","whois.aunic.net","No entries found");
        $this->servers[$i++]=array("co.jp","whois.nic.ad.jp","No match");
        $this->servers[$i++]=array("co.uk","whois.nic.uk","No match for");
        $this->servers[$i++]=array("cq.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("csiro.au","whois.aunic.net","No Data Found");
        $this->servers[$i++]=array("cx","whois.nic.cx","No match");
        $this->servers[$i++]=array("cz","whois.nic.cz","No data found");
        $this->servers[$i++]=array("de","whois.denic.de","No entries found");
        $this->servers[$i++]=array("dk","whois.dk-hostmaster.dk","No entries found");
        $this->servers[$i++]=array("ecn.br","whois.nic.br","No match");
        $this->servers[$i++]=array("ee","whois.eenet.ee","NOT FOUND");
        $this->servers[$i++]=array("edu","whois.verisign-grs.net","No match");
        $this->servers[$i++]=array("edu.au","whois.aunic.net","No Data Found");
        $this->servers[$i++]=array("edu.br","whois.nic.br","No match");
        $this->servers[$i++]=array("eg","whois.ripe.net","No entries found");
        $this->servers[$i++]=array("es","whois.ripe.net","No entries found");
        $this->servers[$i++]=array("esp.br","whois.nic.br","No match");
        $this->servers[$i++]=array("etc.br","whois.nic.br","No match");
        $this->servers[$i++]=array("eti.br","whois.nic.br","No match");	
        $this->servers[$i++]=array("eun.eg","whois.ripe.net","No entries found");
        $this->servers[$i++]=array("emu.id.au","whois.aunic.net","No Data Found");
        $this->servers[$i++]=array("eng.br","whois.nic.br","No match");
        $this->servers[$i++]=array("far.br","whois.nic.br","No match");
        $this->servers[$i++]=array("fi","whois.ripe.net","No entries found");
        $this->servers[$i++]=array("fj","whois.usp.ac.fj","");
        $this->servers[$i++]=array("fj.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("fm.br","whois.nic.br","No match");
        $this->servers[$i++]=array("fnd.br","whois.nic.br","No match");
        $this->servers[$i++]=array("fo","whois.ripe.net","no entries found");
        $this->servers[$i++]=array("fot.br","whois.nic.br","No match");
        $this->servers[$i++]=array("fst.br","whois.nic.br","No match");		
        $this->servers[$i++]=array("fr","whois.nic.fr","No entries found");
        $this->servers[$i++]=array("g12.br","whois.nic.br","No match");
        $this->servers[$i++]=array("gd.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("ge","whois.ripe.net","no entries found");
        $this->servers[$i++]=array("ggf.br","whois.nic.br","No match");
        $this->servers[$i++]=array("gl","whois.ripe.net","no entries found");
        $this->servers[$i++]=array("gr","whois.ripe.net","no entries found");
        $this->servers[$i++]=array("gr.jp","whois.nic.ad.jp","No match");
        $this->servers[$i++]=array("gs","whois.adamsnames.tc","is not registered");
        $this->servers[$i++]=array("gs.cn","whois.cnnic.net.cn","No entries found"); 
        // $this->servers[$i++]=array("gov","whois.nic.gov","");
        $this->servers[$i++]=array("gov.au","whois.aunic.net","No Data Found");
        $this->servers[$i++]=array("gov.br","whois.nic.br","No match");
        $this->servers[$i++]=array("gov.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("gov.hk","whois.hknic.net.hk","No Match for"); 
        $this->servers[$i++]=array("gob.mx","whois.nic.mx","Nombre del Dominio");
        $this->servers[$i++]=array("gs","whois.adamsnames.tc","is not registered");
        $this->servers[$i++]=array("gz.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("gx.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("he.cn","whois.cnnic.net.cn","No entries found");
        $this->servers[$i++]=array("ha.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("hb.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("hi.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("hl.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("hn.cn","whois.cnnic.net.cn","No entries found");  
        $this->servers[$i++]=array("hm","whois.registry.hm","(null)");
        $this->servers[$i++]=array("hk","whois.hknic.net.hk","No Match for");
        $this->servers[$i++]=array("hk.cn","whois.cnnic.net.cn","No entries found");   
        $this->servers[$i++]=array("hu","whois.ripe.net","MAXCHARS:500");        
        $this->servers[$i++]=array("id.au","whois.aunic.net","No Data Found");
        $this->servers[$i++]=array("ie","whois.domainregistry.ie","no match");
        $this->servers[$i++]=array("ind.br","whois.nic.br","No match");
        $this->servers[$i++]=array("imb.br","whois.nic.br","No match");
        $this->servers[$i++]=array("inf.br","whois.nic.br","No match");
        $this->servers[$i++]=array("info","whois.afilias.info","Not found");
        $this->servers[$i++]=array("info.au","whois.aunic.net","No Data Found");
        $this->servers[$i++]=array("it","whois.nic.it","No entries found");
        $this->servers[$i++]=array("idv.tw","whois.twnic.net","NO MATCH TIP");
        $this->servers[$i++]=array("int","whois.iana.org","not found");
        $this->servers[$i++]=array("is","whois.isnic.is","No entries found");
        $this->servers[$i++]=array("il","whois.isoc.org.il","No data was found");
        $this->servers[$i++]=array("jl.cn","whois.cnnic.net.cn","No entries found");
        $this->servers[$i++]=array("jor.br","whois.nic.br","No match"); 
        $this->servers[$i++]=array("jp","whois.nic.ad.jp","No match");
        $this->servers[$i++]=array("js.cn","whois.cnnic.net.cn","No entries found");
        $this->servers[$i++]=array("jx.cn","whois.cnnic.net.cn","No entries found");
        // $this->servers[$i++]=array("kg","whois.domain.kg","");  
        // $this->servers[$i++]=array("kh","whois.khnic.net.kh","");
        $this->servers[$i++]=array("kr","whois.krnic.net","is not registered");
        // $this->servers[$i++]=array("kz","whois.nic.kz","");
        $this->servers[$i++]=array("la","whois.nic.la","NO MATCH");
        $this->servers[$i++]=array("lel.br","whois.nic.br","No match");
        $this->servers[$i++]=array("li","whois.nic.ch","We do not have an entry");
        $this->servers[$i++]=array("lk","whois.nic.lk","No domain registered");
	    $this->servers[$i++]=array("ln.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("lt","ns.litnet.lt","No matches found");
        $this->servers[$i++]=array("lu","whois.dns.lu","No entries found");
        $this->servers[$i++]=array("lv","whois.ripe.net","no entries found");
        $this->servers[$i++]=array("ltd.uk","whois.nic.uk","No match for");
        $this->servers[$i++]=array("mat.br","whois.nic.br","No match");
        $this->servers[$i++]=array("mc","whois.ripe.net","No entries found");
        $this->servers[$i++]=array("med.br","whois.nic.br","No match");	
        $this->servers[$i++]=array("mil","whois.nic.mil","No match");
        $this->servers[$i++]=array("mil.br","whois.nic.br","No match");
        $this->servers[$i++]=array("mn","whois.nic.mn","Domain not found");        
        $this->servers[$i++]=array("mo.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("ms","whois.adamsnames.tc","is not registered");
        $this->servers[$i++]=array("mus.br","whois.nic.br","No match");
        $this->servers[$i++]=array("mx","whois.nic.mx","Nombre del Dominio");
        $this->servers[$i++]=array("name","whois.nic.name","No match");
        $this->servers[$i++]=array("ne.jp","whois.nic.ad.jp","No match");
        $this->servers[$i++]=array("net","whois.verisign-grs.net","No match");
        $this->servers[$i++]=array("net.au","whois.aunic.net","No Data Found");
        $this->servers[$i++]=array("net.br","whois.nic.br","No match");
        $this->servers[$i++]=array("net.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("net.eg","whois.ripe.net","No entries found");
        $this->servers[$i++]=array("net.hk","whois.hknic.net.hk","No Match for"); 
        $this->servers[$i++]=array("net.lu","whois.dns.lu","No entries found");
        $this->servers[$i++]=array("net.mx","whois.nic.mx","Nombre del Dominio");
        $this->servers[$i++]=array("net.uk","whois.nic.uk","No match for ");
        $this->servers[$i++]=array("net.ru","whois.ripn.ru","No entries found");
        $this->servers[$i++]=array("net.tw","whois.twnic.net","NO MATCH TIP");
        // $this->servers[$i++]=array("ng","pgebrehiwot.iat.cnr.it","");
        $this->servers[$i++]=array("nl","whois.domain-registry.nl","is not a registered domain");
        $this->servers[$i++]=array("nm.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("no","whois.norid.no","no matches");
        $this->servers[$i++]=array("nom.br","whois.nic.br","No match");
        $this->servers[$i++]=array("not.br","whois.nic.br","No match");	 
        $this->servers[$i++]=array("ntr.br","whois.nic.br","No match");
        $this->servers[$i++]=array("nx.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("nz","whois.domainz.net.nz","Not Listed");       
        $this->servers[$i++]=array("plc.uk","whois.nic.uk","No match for");
        $this->servers[$i++]=array("odo.br","whois.nic.br","No match");	
        $this->servers[$i++]=array("oop.br","whois.nic.br","No match");
        $this->servers[$i++]=array("or.jp","whois.nic.ad.jp","No match");
        $this->servers[$i++]=array("org","whois.verisign-grs.net","No match");
        $this->servers[$i++]=array("org.au","whois.aunic.net","No Data Found");
        $this->servers[$i++]=array("org.br","whois.nic.br","No match");
        $this->servers[$i++]=array("org.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("org.hk","whois.hknic.net.hk","No Match for"); 
        $this->servers[$i++]=array("org.lu","whois.dns.lu","No entries found");
        $this->servers[$i++]=array("org.ru","whois.ripn.ru","No entries found");
        $this->servers[$i++]=array("org.tw","whois.twnic.net","NO MATCH TIP");
        $this->servers[$i++]=array("org.uk","whois.nic.uk","No match for");
        $this->servers[$i++]=array("pl","nazgul.nask.waw.pl","does not exists");   
        $this->servers[$i++]=array("pp.ru","whois.ripn.ru","No entries found");
        $this->servers[$i++]=array("ppg.br","whois.nic.br","No match");
        $this->servers[$i++]=array("pro.br","whois.nic.br","No match");
        $this->servers[$i++]=array("psi.br","whois.nic.br","No match");
        $this->servers[$i++]=array("psc.br","whois.nic.br","No match");	
        $this->servers[$i++]=array("pt","whois.ripe.net","No entries found");
        $this->servers[$i++]=array("qh.cn","whois.cnnic.net.cn","No entries found");
        $this->servers[$i++]=array("qsl.br","whois.nic.br","No match");		
        $this->servers[$i++]=array("rec.br","whois.nic.br","No match");
        $this->servers[$i++]=array("ro","whois.rotld.ro","No entries found");    
        $this->servers[$i++]=array("ru","whois.ripn.ru","No entries found");
        $this->servers[$i++]=array("sc.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("sd.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("se","whois.nic-se.se","No data found");
        $this->servers[$i++]=array("sg","whois.nic.net.sg","NO entry found");
        $this->servers[$i++]=array("sh","whois.nic.sh","No match for");
        $this->servers[$i++]=array("sh.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("si","whois.arnes.si","No entries found");
        $this->servers[$i++]=array("sk","whois.ripe.net","no entries found");
        $this->servers[$i++]=array("slg.br","whois.nic.br","No match");	
        $this->servers[$i++]=array("sm","whois.ripe.net","no entries found");   
        $this->servers[$i++]=array("sn.cn","whois.cnnic.net.cn","No entries found");   
        $this->servers[$i++]=array("srv.br","whois.nic.br","No match");
        $this->servers[$i++]=array("st","whois.nic.st","No entries found");   
        $this->servers[$i++]=array("sx.cn","whois.cnnic.net.cn","No entries found");
        $this->servers[$i++]=array("tc","whois.adamsnames.tc","is not registered");
        $this->servers[$i++]=array("th","whois.nic.uk","No entries found");
        $this->servers[$i++]=array("tj.cn","whois.cnnic.net.cn","No entries found");
        $this->servers[$i++]=array("tmp.br","whois.nic.br","No match");
        // $this->servers[$i++]=array("tm","whois.nic.tm",""); 
        $this->servers[$i++]=array("to","whois.tonic.to","No match");
        $this->servers[$i++]=array("tr","whois.ripe.net","Not found in database");
        $this->servers[$i++]=array("trd.br","whois.nic.br","No match"); 
        $this->servers[$i++]=array("tur.br","whois.nic.br","No match"); 
        $this->servers[$i++]=array("tv","whois.tv","MAXCHARS:75"); // ! .tv domains are limited in requests of WHOIS information at the server whois.tv up to 20 requests
        $this->servers[$i++]=array("tv.br","whois.nic.br","No match");
        $this->servers[$i++]=array("tw","whois.twnic.net","NO MATCH TIP");
        $this->servers[$i++]=array("tw.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("uk","whois.thnic.net","No match for");
        // $this->servers[$i++]=array("us","whois.us","");
        $this->servers[$i++]=array("va","whois.ripe.net","No entries found");
        $this->servers[$i++]=array("vet.br","whois.nic.br","No match");    
        $this->servers[$i++]=array("vg","whois.adamsnames.tc","is not registered");
        $this->servers[$i++]=array("wattle.id.au","whois.aunic.net","No Data Found");
        $this->servers[$i++]=array("ws","whois.worldsite.ws","No match for");
        $this->servers[$i++]=array("xj.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("xz.cn","whois.cnnic.net.cn","No entries found"); 
        $this->servers[$i++]=array("yn.cn","whois.cnnic.net.cn","No entries found"); 
        // $this->servers[$i++]=array("za","whois.frd.ac.za","");
        $this->servers[$i++]=array("zlg.br","whois.nic.br","No match");
        $this->servers[$i++]=array("zj.cn","whois.cnnic.net.cn","No entries found");
 
        $this->domain=$str_domainname;
     }

    /**
    * Returns the whois data of the domain
    * @return string $whoisdata Whois data as string
    * @desc Returns the whois data of the domain
    */
    function info(){
        if($this->is_valid()){

            $tldname=$this->get_tld();
            $domainname=$this->get_domain();
            $whois_server=$this->get_whois_server();
            
            // If tldname have been found
            if($whois_server!=""){
                // Getting whois information
                $fp = fsockopen($whois_server,43);

                $dom=$domainname.".".$tldname;
                fputs($fp, "$dom\r\n");

                // Getting string
                $string="";
                while(!feof($fp)){
                    $string.=fgets($fp,128);
                }
                fclose($fp);
                return $string;
            }else{
                return "No whois server for this tld in list!";
            }
        }else{
            return "Domainname isn't valid!";
        }
    }

    /**
    * Returns the whois data of the domain in HTML format
    * @return string $whoisdata Whois data as string in HTML
    * @desc Returns the whois data of the domain  in HTML format
    */
    function html_info(){
        return nl2br($this->info());
    }

    /**
    * Returns name of the whois server of the tld
    * @return string $server the whois servers hostname
    * @desc Returns name of the whois server of the tld
    */
    function get_whois_server(){
            $found=false;
            $tldname=$this->get_tld();
            for($i=0;$i<count($this->servers);$i++){
                if($this->servers[$i][0]==$tldname){
                    $server=$this->servers[$i][1];
                    $full_dom=$this->servers[$i][3];
                    $found=true;
                }
            }
            return $server;
    }

    /**
    * Returns the tld of the domain without domain name
    * @return string $tldname the tlds name without domain name
    * @desc Returns the tld of the domain without domain name
    */
    function get_tld(){
       // Splitting domainname
       $domain=split("\.",$this->domain);
       if(count($domain)>2){
           $domainname=$domain[0];
           for($i=1;$i<count($domain);$i++){
               if($i==1){
                  $tldname=$domain[$i];
               }else{
                  $tldname.=".".$domain[$i];
               }
            }
       }else{
           $domainname=$domain[0];
           $tldname=$domain[1];
       }
       return $tldname;
    }

    /**
    * Returns all tlds which are supported by the class
    * @return array $tlds all tlds as array
    * @desc Returns all tlds which are supported by the class
    */    
    function get_tlds(){
    	$tlds="";
    	for($i=0;$i<count($this->servers);$i++){
    		$tlds[$i]=$this->servers[$i][0];
    	}
    	return $tlds;
    }

    /**
    * Returns the name of the domain without tld
    * @return string $domain the domains name without tld name
    * @desc Returns the name of the domain without tld
    */
    function get_domain(){
       // Splitting domainname
       $domain=split("\.",$this->domain);
       return $domain[0];
    }

    /**
    * Returns the string which will be returned by the whois server of the tld if a domain is avalable
    * @return string $notfound  the string which will be returned by the whois server of the tld if a domain is avalable
    * @desc Returns the string which will be returned by the whois server of the tld if a domain is avalable
    */
    function get_notfound_string(){
       $found=false;
       $tldname=$this->get_tld();
       for($i=0;$i<count($this->servers);$i++){
           if($this->servers[$i][0]==$tldname){
               $notfound=$this->servers[$i][2];
           }
       }
       return $notfound;
    }
    
    /**
    * Returns if the domain is available for registering
    * @return boolean $is_available Returns 1 if domain is available and 0 if domain isn't available
    * @desc Returns if the domain is available for registering
    */
    function is_available(){
        $whois_string=$this->info();
        $not_found_string=$this->get_notfound_string();
        
        $domain=$this->domain;
        $whois_string2=ereg_replace("$domain","",$whois_string);
 
        $array=split(":",$not_found_string);
                
        if($array[0]=="MAXCHARS"){
        	if(strlen($whois_string2)<=$array[1]){
        		return true;
        	}else{
        		return false;
        	}        	
        }else{
        	if(preg_match("/".$not_found_string."/i",$whois_string)){
            	return true;
        	}else{
            	return false;
        	}
        }
    }

    /**
    * Returns if the domain name is valid
    * @return boolean $is_valid Returns 1 if domain is valid and 0 if domain isn't valid
    * @desc Returns if the domain name is valid
    */
    function is_valid(){
        if(ereg("^[a-zA-Z0-9\-]{3,}$",$this->get_domain()) && !preg_match("/--/",$this->get_domain())){
            return true;
        }else{
            return false;
        }
    }
}
?>
