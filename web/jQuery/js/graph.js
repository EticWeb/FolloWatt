// cf http://www.tuttoaster.com/creating-bar-graphs-using-jquery-ui/
$.fn.graph = function(options){
	var defaults = {height:300,width:500,data:'',categories:'',legend:"Graph"};
	var options = $.extend(defaults, options);
	
	
	var root = $(this).addClass("ui-widget-content");
	root.wrap("<div class=' ui-widget ' />");
	var parent = root.parent();
	
	parent.prepend("<h3 class='ui-helper-reset ui-widget-header '>"+options.legend+"</h3>");
	parent.css({height:options.height,width:options.width});
	root.css("position","relative");
	
	var i=0,max_val=0,scale,datalen=0,temp,w;
	
	
	for(i=0;i<options.data.length;i++)
	{
		if(options.data[i]>=max_val)
		max_val = options.data[i];
	}
	max_val += 10;
	
	scale = options.height/options.data.length;
	i=options.height;
	var bg = jQuery("<div />",{ css:{height:scale - 1 }  }).addClass("ui-helper-reset ui-widget-bg");
	var width = Math.floor((options.width-70)/options.data.length - (options.categories.length/1.5));
	var bar = jQuery("<div />",{ css:{width:width}  }).addClass("ui-helper-reset ui-state-active ui-widget-bar");
     	
	var counter = 0;
	
	while(i>=0)
	{
		temp = bg.clone().html(max_val - counter);
		root.append(temp);
		i = i - scale;
		counter = counter + max_val/8;
		datalen++;
	}
	
	w= 40;
	
	 for(i=0;i<options.categories.length;i++)
	   {
		
		temp =  Math.floor(options.data[i]/max_val * datalen * scale ) - scale;
		root.append(bar.clone().css({height:temp,left:w}).html(options.categories[i])); 
		
		
		w = w + width + 10;
		
	  }
	$(".ui-widget-bg:last").css("height",0);
	$(".ui-widget-bar").hover(function(){
					  
					   $(this).toggleClass('ui-state-active ui-state-hover');
					   $(".ui-widget-bar").not(this).addClass('ui-priority-secondary');
					   
					   },function(){
						   
						   $(this).toggleClass('ui-state-active ui-state-hover');
						$(".ui-widget-bar").not(this).removeClass('ui-priority-secondary');    
						   });	

	
	}