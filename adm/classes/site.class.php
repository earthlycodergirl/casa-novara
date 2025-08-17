<?php
class Site{
    public $Testimonials=array();
    public $Blog=array();
    public $ContactInfo = array();
    public $NavDests = array();

    public function __construct(){

    }



    public function getNav(){
      $nav = array('city'=>array(),'town'=>array(),'area'=>array());

      $getC = new SqlIt("SELECT * FROM locations_cities WHERE is_featured = ? ORDER BY location ASC","select",array(1));
      if($getC->NumResults > 0){
        foreach($getC->Response as $gc){
          $nav['city'][$gc->city_id]['title'] = utf8_encode($gc->location);
          $nav['city'][$gc->city_id]['link'] = urlencode(strtolower($gc->location));
          $nav['city'][$gc->city_id]['id'] = $gc->city_id;
        }
      }

      $getT = new SqlIt("SELECT * FROM locations_towns WHERE is_featured = ? ORDER BY town_name ASC","select",array(1));
      if($getT->NumResults > 0){
        foreach($getT->Response as $gc){
          $nav['town'][$gc->town_id]['title'] = utf8_encode($gc->town_name);
          $nav['town'][$gc->town_id]['link'] = urlencode(strtolower($gc->town_name));
          $nav['town'][$gc->town_id]['id'] = $gc->town_id;
        }
      }

      $getA = new SqlIt("SELECT * FROM locations_areas WHERE is_featured = ? ORDER BY area_name ASC","select",array(1));
      if($getA->NumResults > 0){
        foreach($getA->Response as $gc){
          $nav['area'][$gc->area_id]['title'] = utf8_encode($gc->area_name);
          $nav['area'][$gc->area_id]['link'] = urlencode(strtolower($gc->area_name));
          $nav['area'][$gc->area_id]['id'] = $gc->area_id;
        }
      }

      $this->NavDests = $nav;

    }



    public function getTestimonials($cnt=0){
        $limit_query = '';
        if($cnt != 0){
            $limit_query = ' LIMIT '.$cnt;
        }

        $getTest = new SqlIt("SELECT * FROM site_testimonials  ORDER BY test_id DESC ".$limit_query,"select",array());
        if($getTest->NumResults != 0){
            foreach($getTest->Response as $gt){
                $this->Testimonials[] = $gt;
            }
        }
    }

    public function getBlog($cnt=0,$id=0){
      global $lang;
        $limit_query = $where_query = '';
        if($cnt != 0){
            $limit_query = ' LIMIT '.$cnt;
        }

        if($id > 0){
          $where_query = ' AND blog_id = '.$id;
        }

        $getBlog= new SqlIt("SELECT * FROM site_blog WHERE lang = ? ".$where_query." ORDER BY blog_id DESC ".$limit_query,"select",array($lang));
        if($getBlog->NumResults != 0){
            foreach($getBlog->Response as $gt){
                $this->Blog[] = $gt;
            }
        }
    }

    public function getArticle($blog_id=0){
        if($blog_id != 0){
            $getA = new SqlIt("SELECT * FROM site_blog WHERE blog_id = ?","select",array($blog_id));

            if($getA->NumResults != 0){
                $bb = $getA->Response[0];
                $this->BlogA['title'] = $bb->title;
                $this->BlogA['author'] = $bb->author;
                $this->BlogA['date'] = $bb->posted;
                $this->BlogA['content'] = $bb->content;
            }
        }
    }

    public function getArtPub($blog_id=0){
        $this->Art = $this->Prev = $this->Next = array();
        if($blog_id != 0){
            $getA = new SqlIt("
              select * from site_blog where blog_id = ?
              union all
              (select * from site_blog where blog_id < ? order by blog_id desc limit 1)
              union all
              (select * from site_blog where blog_id > ? order by blog_id asc limit 1) ","select",array($blog_id,$blog_id,$blog_id));

            if($getA->NumResults != 0){
                foreach($getA->Response as $bb){
                  if($bb->blog_id == $blog_id){
                    $this->Art['title'] = $bb->title;
                    $this->Art['author'] = $bb->author;
                    $this->Art['date'] = $bb->posted;
                    $this->Art['content'] = $bb->content;
                    $this->Art['img'] = $bb->main_img;
                    $this->Art['id'] = $bb->blog_id;
                  }
                  if($bb->blog_id < $blog_id){
                    $this->Prev['title'] = $bb->title;
                    $this->Prev['id'] = $bb->blog_id;
                    $this->Prev['img'] = $bb->main_img;
                  }
                  if($bb->blog_id > $blog_id){
                    $this->Next['title'] = $bb->title;
                    $this->Next['id'] = $bb->blog_id;
                    $this->Next['img'] = $bb->main_img;
                  }
                }


            }
        }
    }

    public function getContact(){

      $this->ContactInfo = array
      (
        'contact_page'=>array(),
        'social'=>array(),
        'contact_emails'=>array(),
        'request_emails'=>array(),

      );

      $getC = new SqlIt("SELECT * FROM site_contact","select",array());
      if($getC->NumResults > 0){
        foreach($getC->Response as $gg){
          $this->ContactInfo[$gg->contact_section][] = array(
            'id'=>$gg->contact_id,
            'type'=>$gg->contact_type,
            'val'=>$gg->contact_value,
            'icon'=>$gg->contact_display
          );
        }
      }
    }
}
?>