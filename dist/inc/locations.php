<?php
switch($_GET['location']){
  case 1808:
    $dest_head = $lan['ho']['real'].' Cancun, Mexico';
    $dest_txt = 'Cancun, Fifty years of Premier tourism development in the Mexican Caribbean. Home to the international airport of Cancun and a Hotel zone unlike any other. Stretching out around the lagoon of Nichupté making it a premier tourism destination for world-class entertainment. Putting the Mexican Caribbean at the forefront of international investment. Whether they are beachfront condominiums or gated residential communities in the downtown area, Cancun is rich in opportunity.';
    $dest_txt_es = 'Cancún: cincuenta años de desarrollo turístico Premier en el Caribe Mexicano. Hogar del aeropuerto internacional de Cancún y una zona hotelera como ninguna otra. Se extiende alrededor de la laguna de Nichupté, lo que lo convierte en un destino turístico de primer nivel para el entretenimiento de clase mundial. Poniendo al Caribe mexicano a la vanguardia de la inversión internacional. Ya sean condominios frente al mar o comunidades residenciales cerradas en el centro de la ciudad, Cancún es rico en oportunidades.';
    $show_dest = 1;
    $dest_class = 'cancun-bg';
    $dest_thumb = 'cancun.jpg';
    break;
  case 1810:
    $dest_head = $lan['ho']['real'].' Playa del Carmen, Mexico';
    $dest_txt = 'Playa del Carmen, The beating heart of the Riviera Maya, Famous for its pedestrian boulevard the ‘Fifth Av.’ where you find world class shopping, fine dining and all the local flavors you are looking for. Always within walking distance from the beach. You are sure to find high value return investment opportunities. Whether they are beachfront condominiums, or american style villas in private residential eco-communities like Playacar, Mayakoba and many others.';
    $dest_txt_es = 'Playa del Carmen, el corazón palpitante de la Riviera Maya, famosa por su bulevar peatonal la Quinta Av. Donde encontrará tiendas de clase mundial, excelentes restaurantes y todos los sabores locales que está buscando. Siempre a poca distancia de la playa. Seguro que encontrará oportunidades de inversión de alto valor. Ya sean condominios frente al mar o villas de estilo americano en eco-comunidades residenciales privadas como Playacar, Mayakoba y muchas otras.';
    $show_dest = 1;
    $dest_class = 'playa-bg';
    $dest_thumb = 'pdc.jpg';
    break;
  case 1811:
    $dest_head = $lan['ho']['real'].' Tulum, Mexico';
    $dest_txt = 'Tulum, the youngest in terms of developments in the Riviera Maya. You will find that Tulum offers an eco-chic feel, with the most beautiful beaches in the Riviera. Michelin starred fine dining, combined with a jungle-like hotel zone. Downtown Tulum is home to art venues and an eco-friendly lifestyle, showcasing some of the newest and most modern constructions.';
    $dest_txt_es = 'Tulum, el más joven en cuanto a desarrollos en la Riviera Maya. Descubrirás que Tulum ofrece un ambiente eco-chic, con las playas más hermosas de la Riviera. Buena comida con estrella Michelin, combinada con una zona hotelera similar a la jungla. El centro de Tulum alberga lugares de arte y un estilo de vida ecológico, que muestra algunas de las construcciones más nuevas y modernas.';
    $show_dest = 1;
    $dest_class = 'tulum-bg';
    $dest_thumb = 'tulum.jpg';
    break;
  case 2440:
    $dest_head = $lan['ho']['real'].' Puerto Morelos, Mexico';
    $dest_txt = 'Puerto Morelos, A hidden gem in terms of investment that offers a real ‘small-town feel’ with a lot of room for growth. The bay area provides some of the best snorkeling and fresh seafood restaurants in the whole of the Riviera Maya. World renown for its mesoamerican barrier reef system. Puerto Morelos is pristine with protected mangrove forests surrounding it.';
    $dest_txt_es = 'Puerto Morelos, una joya escondida en términos de inversión que ofrece una verdadera "sensación de pueblo pequeño" con mucho espacio para crecer. El área de la bahía ofrece algunos de los mejores restaurantes para practicar snorkel y mariscos frescos de toda la Riviera Maya. Conocido mundialmente por su sistema de barrera arrecifal mesoamericano. Puerto Morelos es prístino con bosques de manglares protegidos que lo rodean.';
    $show_dest = 1;
    $dest_class = 'puerto-bg';
    $dest_thumb = 'puerto-morelos.jpg';
    break;
  case 1809:
    $dest_head = $lan['ho']['real'].' Cozumel, Mexico';
    $dest_txt = 'The island of Cozumel, A world-class destination, famous for its turquoise waters. Home to some of the best scuba diving in the world. This small town ‘big city feel’ provides you with the most modern services. You will find this to be one of the most visited islands in the Caribbean. Offering excellent return on real estate ventures. Modern condominiums and new construction are plentiful. Being that the largest cruise ships visit the ports of this beautiful island, you can be sure to attract the international attention you seek for your ventures in the Caribbean.';
    $dest_txt_es = 'La isla de Cozumel, un destino de clase mundial, famoso por sus aguas turquesas. Hogar de algunos de los mejores lugares para bucear en el mundo. Esta pequeña ciudad "sensación de gran ciudad" le ofrece los servicios más modernos. Descubrirás que esta es una de las islas más visitadas del Caribe. Ofrece una excelente rentabilidad en proyectos inmobiliarios. Los condominios modernos y las nuevas construcciones abundan. Siendo que los cruceros más grandes visitan los puertos de esta hermosa isla, puede estar seguro de atraer la atención internacional que busca para sus aventuras en el Caribe.';
    $show_dest = 1;
    $dest_class = 'cozumel-bg';
    $dest_thumb = 'holbox.jpg';
    break;
  case 2449:
    $dest_head = $lan['ho']['real'].' Bacalar, Mexico';
    $dest_txt = 'Living in Laguna Bacalar, Mexico. Bacalar is an amazing lake in the southern tip of Quintana Roo State. This is a fresh water lake fed by underground cenotes, but it is so grand and vibrant that it look like the Caribbean Sea. The lake is about 55km or 34 miles from tip to tip and 2km or 1.2 miles at its widest point. A quiet town with a wide variety of nature and wildlife.';
    $dest_txt_es = 'Vive en Laguna Bacalar, México. Bacalar es un lago increíble en el extremo sur del estado de Quintana Roo. Este es un lago de agua dulce alimentado por cenotes subterráneos, pero es tan grandioso y vibrante que parece el Mar Caribe. El lago tiene unos 55 km o 34 millas de punta a punta y 2 km o 1,2 millas en su punto más ancho. Un pueblo tranquilo con una gran variedad de naturaleza y vida salvaje.';
    $show_dest = 1;
    $dest_class = 'bacalar-bg';
    $dest_thumb = 'bacalar.jpg';
    break;
}

if(isset($_GET['counties']) && !empty($_GET['counties'])){
  if(in_array(29,$_GET['counties'])){
    $dest_head = $lan['ho']['real'].' Akumal, Mexico';
    $dest_txt = 'Akumal, known as the home of the turtle. You will find that this eco-oriented destination is home to wildlife sanctuaries and beachfront properties alike. Where the community values the abundance of wildlife and nature that we all enjoy so much here, in the Caribbean. Whether you are looking for a villa near the beach, or a modern condominium complex to invest in. You are sure to find properties here in Akumal that feel “off the beaten path”.';
    $dest_txt_es = 'Akumal, conocido como el hogar de la tortuga. Descubrirá que este destino ecológico alberga santuarios de vida silvestre y propiedades frente a la playa por igual. Donde la comunidad valora la abundancia de vida silvestre y naturaleza que todos disfrutamos tanto aquí, en el Caribe. Ya sea que esté buscando una villa cerca de la playa o un moderno complejo de condominios para invertir. Seguro que encontrará propiedades aquí en Akumal que se sienten “fuera de lo común”.';
    $show_dest = 1;
    $dest_class = 'akumal-bg';
    $dest_thumb = 'akumal.jpg';
  }
  if(in_array(26,$_GET['counties'])){
    $dest_head = $lan['ho']['real'].' Puerto Aventuras, Mexico';
    $dest_txt = 'Puerto Aventuras, An adventure in paradise. This gated residential area offers luxury housing with a private marina, whether you are looking for new construction or townhouse villas that have private access to the marina. It is home to a large ex-pat community, designed to be exclusive while offering modern commodities. Whether you enjoy golfing on its private golf courses or visiting the Yacht club. You can be sure to find investment opportunities that have high interest and returns.';
    $dest_txt_es = 'Puerto Aventuras, una aventura en el paraíso. Esta zona residencial cerrada ofrece viviendas de lujo con puerto deportivo privado, ya sea que esté buscando una nueva construcción o villas adosadas que tengan acceso privado al puerto deportivo. Es el hogar de una gran comunidad de expatriados, diseñada para ser exclusiva y ofrecer comodidades modernas. Tanto si le gusta jugar al golf en sus campos de golf privados como si visita el club náutico. Puede estar seguro de que encontrará oportunidades de inversión que tengan un gran interés y rentabilidad.';
    $show_dest = 1;
    $dest_thumb = 'puerto-aventuras.jpg';
  }
}

if($lang == 'es'){
  $dest_txt = $dest_txt_es;
}

?>