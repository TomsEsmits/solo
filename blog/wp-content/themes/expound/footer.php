				</div>
			</div>
		</div>
	</div>

	<?php 
		if(is_user_logged_in()){
	?>
		<div id="leave-popup">
			<div id="blog-popup-form" class="box">
				<div class="close-leave-popup">
					<span>&#10006;</span>
				</div>
				<h2>Before you leave, search for your replacement parts here</h2>
				<div class="responsive-call">Or Call Us <a href="tel:8888480144">888.848.0144</a></div>
				<div id="blog-popup-form">
					<div>
						<select id="make" onchange="getCategoryAjax( this.options[this.selectedIndex].value, 'model' );">
							<option value="~" disabled="disabled" selected="selected">Step 1. Select Auto Make</option>
						</select>
					</div>
					<div>
						<select id="model" class="l-1" onchange="getCategoryAjax( 'year', 'year' );">
							<option value="~" disabled="disabled" selected="selected">Step 2. Select Auto Model</option>
						</select>
					</div>
					<div>
						<select id="year" class="l-1 l-2" onchange="getCategoryAjax( 'engine_type', 'engine-type' );">
							<option value="~" disabled="disabled" selected="selected">Step 3. Select Auto Year</option>
						</select>
					</div>
					<div>
						<select id="engine-type" class="l-1 l-2 l-3">
							<option value="~" disabled="disabled" selected="selected">Step 4. Select Engine Type</option>
						</select>
					</div>
					<div>
						<a href="#" id="popup-form-submit">Search</a>
					</div>
				</div>
			</div>
		</div>		

		<style>
			#leave-popup{
				display: flex;
				box-sizing: border-box;
				opacity: 0;
				width: 600px;
				max-width: 100%;				
				position: fixed;
				transform: translate(100%, -50%);
				left: 50%;
				padding: 30px;
				background: #fff;
				z-index: 99999999;
				-webkit-box-shadow: 0px 2px 33px -4px #000000; 
				box-shadow: 0px 2px 33px -4px #000000;
				-webkit-transition: all .5s ease-out;
				-moz-transition: all .5s ease-out;
				-o-transition: all .5s ease-out;
				transition: all .5s ease-out;
			}
			#leave-popup.active{
				display: flex;
				opacity: 1;
				top: 50%;				
				transform: translate(-50%, -50%);
			}
			#blog-popup-form{
				text-align: center;
			}
			#leave-popup select{
				width: 350px;
				max-width: 100%;
				box-sizing: border-box;
				padding: 10px 20px!important;
				margin-bottom: 10px;
				border: solid 1px #ccc;
			}
			.close-leave-popup{
				text-align: right;
			}
			.close-leave-popup span{
				display: inline-block;
				cursor: pointer;				
				-webkit-transition: -webkit-transform .4s ease-in-out;
				-ms-transition: -ms-transform .4s ease-in-out;
				transition: transform .48s ease-in-out;  
			}
			.close-leave-popup span:hover{
				color: #650d19;
				transform:scale(1.5);
				-ms-transform:scale(1.5);
				-webkit-transform:scale(1.5);
			}
			#leave-popup .responsive-call{
				padding-bottom: 30px;
			}
			#popup-form-submit{
				background: #4587b9;
				color: #fff;
				box-sizing: border-box;
				padding: 7px 20px;
				margin-top: 15px;
				display: inline-block;
			}
			#popup-form-submit:hover{
				text-decoration: none;
				background: #03416f;
				-webkit-transition: all .5s ease-out;
				-moz-transition: all .5s ease-out;
				-o-transition: all .5s ease-out;
				transition: all .5s ease-out;
			}
		</style>

		
	<?php 
		}
	?>	

	<?php
		wp_footer();
		the_block('footer');
	?>
	
	<style>
		.entry-content img{
			max-width:100%;
			height: auto;
		}
	</style>
	<?php
	/*
		$url = "https://" . $_SERVER[ "SERVER_NAME" ] . "/";
	?>
	<script type="text/javascript" src="<?php echo $url; ?>js/varien/js.js"></script>
	<script type="text/javascript" src="<?php echo $url; ?>js/varien/form.js"></script>
	*/ ?>
	<script>
window['_fs_debug'] = false;
window['_fs_host'] = 'fullstory.com';
window['_fs_org'] = '6NX5T';
window['_fs_namespace'] = 'FS';
(function(m,n,e,t,l,o,g,y){
    if (e in m) {if(m.console && m.console.log) { m.console.log('FullStory namespace conflict. Please set window["_fs_namespace"].');} return;}
    g=m[e]=function(a,b){g.q?g.q.push([a,b]):g._api(a,b);};g.q=[];
    o=n.createElement(t);o.async=1;o.src='https://'+_fs_host+'/s/fs.js';
    y=n.getElementsByTagName(t)[0];y.parentNode.insertBefore(o,y);
    g.identify=function(i,v){g(l,{uid:i});if(v)g(l,v)};g.setUserVars=function(v){g(l,v)};
    g.identifyAccount=function(i,v){o='account';v=v||{};v.acctId=i;g(o,v)};
    g.clearUserCookie=function(c,d,i){if(!c || document.cookie.match('fs_uid=[`;`]*`[`;`]*`[`;`]*`')){
    d=n.domain;while(1){n.cookie='fs_uid=;domain='+d+
    ';path=/;expires='+new Date(0).toUTCString();i=d.indexOf('.');if(i<0)break;d=d.slice(i+1)}}};
})(window,document,window['_fs_namespace'],'script','user');
</script>
</body>
</html>