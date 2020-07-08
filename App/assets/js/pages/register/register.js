$(function () {
    // steps
    var step, currentStep, nextStep, previousStep;

    // style
    var left, opacity, scale, animating;

    $('form.register .next').click(function(e) { 
        e.preventDefault(); 
        
        if(animating) return false;
        animating = true;
        
        step = $(this).data('step');
        currentStep = $(`.register-step-${step}`);
        nextStep = $(`.register-step-${step+1}`);

        $("#progressbar li").eq($("fieldset").index(nextStep)).addClass("active");
    
        //show the next fieldset
        nextStep.show(); 
        
        //hide the current fieldset with style
        currentStep.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50)+"%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                currentStep.css({
                    'transform': 'scale('+scale+')',
                    'position': 'absolute',
                    'width': '96%'
                });
                nextStep.css({'left': left, 'opacity': opacity});
        }, 
            duration: 800, 
            complete: function(){
                currentStep.hide();
                animating = false;
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });

    $("form.register .previous").click(function(e) {
        e.preventDefault(); 

        if(animating) return false;
        animating = true;
        
        step = $(this).data('step');
        currentStep = $(`.register-step-${step}`);
        previousStep = $(`.register-step-${step-1}`);
        
        //de-activate current step on progressbar
        $("#progressbar li").eq($("fieldset").index(currentStep)).removeClass("active");
        
        //show the previous fieldset
        previousStep.show(); 
        //hide the current fieldset with style
        currentStep.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1-now) * 50)+"%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                currentStep.css({'left': left});
                previousStep.css({
                    'transform': 'scale('+scale+')', 
                    'opacity': opacity
                });
            }, 
            duration: 800, 
            complete: function(){
                currentStep.hide();
                animating = false;
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });
    
    // Adapts the select multiple of the selectric to accept only 2 checked options
    var mainCategories = $('.main-categories').selectric();
    
    mainCategories.on('selectric-before-change', function(event, element, selectric) {                
        if (selectric.state.selectedIdx.length == 3) {
            $(`.selectric-main-categories ul li:nth-child(${selectric.state.selectedIdx[0] + 1})`).removeClass('selected');
            selectric.state.selectedIdx.shift();
        }        
    });
});

