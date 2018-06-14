// Footer mobile show hide block.
jQuery(document).ready(function(){
    jQuery('.textwidget h5').append('<i class="fa fa-angle-down"></i>');
    jQuery('.footer-widget-3 .footer-right > h5 > i').addClass('fa-angle-up');
    jQuery('.footer-widget-3 .footer-right > p').addClass('active');
    jQuery('.footer-widget-3 .footer-right > form').addClass('active');
});

function footeraccordion(){
    if(jQuery(window).width()<=767){
        jQuery('.textwidget h5').on('click', function(e){
            e.preventDefault();
            jQuery(this).find("i").toggleClass('fa-angle-up');
            jQuery(this).next().next().toggleClass('active');
            jQuery(this).next().next().next().toggleClass('active');
            var notShow = jQuery('.site-footer .textwidget').children().children('h5').not(this);
            if(notShow.children('i').hasClass('fa-angle-up')){
                notShow.children('i').removeClass('fa-angle-up');
                notShow.next().next().removeClass('active');
                notShow.next().next().next().removeClass('active');
            }
        });
    }
}

jQuery(window).load(footeraccordion);
var resizeId1;
jQuery(window).resize(function() {
    clearTimeout(resizeId1);
    resizeId1 = setTimeout(footeraccordion, 500);
});


//Home content mobile custom script for Quick OverView
jQuery(document).ready(function(){
    jQuery('.home .left-panel-main .left-panel h4').append('<i class="fa fa-angle-down"></i>');
    jQuery('.home .left-panel-main .left-panel:last-child h4 i').addClass('fa-angle-up');
    jQuery('.home .left-panel-main .left-panel:last-child .search-form').addClass('active');
});

function homeoverviewshowhide(){
    if(jQuery(window).width()<=767){
        jQuery('.home .left-panel-main .left-panel h4').on('click', function(e){
            e.preventDefault();
            jQuery(this).find("i").toggleClass('fa-angle-up');
            jQuery(this).next().next().toggleClass('active');
            jQuery(this).next().next().next().toggleClass('active');
            var notShow = jQuery('.home .left-panel-main .left-panel h4').not(this);
            if(notShow.children('i').hasClass('fa-angle-up')){
                notShow.children('i').removeClass('fa-angle-up');
                notShow.next().next().removeClass('active');
            }
        });
    }
}
jQuery(window).load(homeoverviewshowhide);
var resizeId2;
jQuery(window).resize(function() {
    clearTimeout(resizeId2);
    resizeId2 = setTimeout(homeoverviewshowhide, 500);
});

//Location page resize map

function locationHeightMap(){
    var heightSet = jQuery('.m-loc-bottom .clinic_front img').height();
    jQuery('.mobile .loc-right-panel.m-loc-bottom').css({"height" : heightSet})
}
jQuery(window).load(locationHeightMap);
var resizeId3;
jQuery(window).resize(function() {
    clearTimeout(resizeId3);
    resizeId3 = setTimeout(locationHeightMap, 500);
});

//Occ Health Page Accordian
jQuery(document).on("click", ".qualitycare_more", function(){
    jQuery('.qualitycare_showmore').toggle();
    var linktext= jQuery(this).text();
    if(linktext=='More...') {     
   
            jQuery('.qualitycare_more').html('Less...');
         
        } else {
       	
            jQuery('.qualitycare_more').html('More...');

        }

    //$('.qualitycare_more').hide();
});
	jQuery(document).on("click", ".tailorprogram_more", function(){
	
    jQuery('.tailorprogram_showmore').toggle();

    var linktext= jQuery(this).text();
    if(linktext=='More...') {     
   
            jQuery('.tailorprogram_more').html('Less...');
         
        } else {
       	
            jQuery('.tailorprogram_more').html('More...');

        }
    //$('.tailorprogram_more').hide();
});
	jQuery(document).on("click", ".extensiverange_more", function(){
	
    jQuery('.extensiverange_showmore').toggle();

    var linktext= jQuery(this).text();
    if(linktext=='More...') {     
   
            jQuery('.extensiverange_more').html('Less...');
         
        } else {
       	
            jQuery('.extensiverange_more').html('More...');

        }
    //$('.extensiverange_more').hide();
});

//Review page set Height for Iframe
/*
function setHeightForReviewIframe(){
    var heightSet = jQuery("#bfpublish body .bodyContainer").height();
    console.log(heightSet);
}
jQuery(window).load(setHeightForReviewIframe);
jQuery(window).resize(setHeightForReviewIframe);*/
