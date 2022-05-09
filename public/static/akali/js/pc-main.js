
$(function () {
    $('.fuwu_bt .hyp').on('click', function () {
        let $ul,$li,_l,li_h,ul_pt;
        $ul = $('.recommend-content ul');
        $li = $ul.find('li');
        _l = Math.ceil($li.length / 10)-1;
        li_h = $li.outerHeight()+5;
        ul_pt = Math.abs($ul.position().top);
        if(ul_pt/li_h>=_l){
            $ul.animate({top:0},200);
        }else{
            $ul.animate({top:'-'+(ul_pt+li_h)+'px'},200);
        }
    });
    
    $('.tti span').on('click', function () {
        let $ul,$li,_l,li_h,ul_pt;
        $ul = $('.tti-cont ul');
        $li = $ul.find('li');
        _l = $li.length-1;
        li_h = $li.outerHeight()+10;
        ul_pt = Math.abs($ul.position().top);
        if(ul_pt/li_h>=_l){
            $ul.animate({top:0},200);
        }else{
            $ul.animate({top:'-'+(ul_pt+li_h)+'px'},200);
        }
    });

})

