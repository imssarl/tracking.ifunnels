<form action="#res" method="POST" class="wh" style="width:50%;">
	<fieldset>
		<legend>URL Mixer</legend>
		<ol>
			<li>
				<label>URLs</label>
				<textarea id="urls" style="height:100px;"></textarea>
			</li>
			<li>
				<label>Regular</label><input type="checkbox" class="output" id="regular" checked='1' name="regular" value="1"/>
			</li>
			<li>
				<label>Quotes</label><input type="checkbox" class="output" id="quotes" name="quotes" value="2"/>
			</li>			
			<li>
				<label>Brackets</label><input type="checkbox" class="output" id="brackets" name="brackets" value="3"/>
			</li>			
			<li>
				<label></label>
				<input type="button" value="Generate" id="generate" />
			</li>
		</ol>
		
	</fieldset>
	<fieldset style="display:none;" id="field_res">
		<legend></legend>
		<ol>
			<li>
				<label>Result</label>
				<textarea id="res" name="result" style="height:200px;"></textarea>
			</li>
			<li>
				<label>File name</label>
				<input type="text" name="name" />
			</li>
			<li>
				<label></label>
				<input type="submit" name="export" value="Export" />
			</li>			
		</ol>
	</fieldset>
	
</form>

<script type="text/javascript">
{literal}
	window.addEvent('domready', function(){
		
		$('generate').addEvent('click',function(){
			if(!$('urls').value) {
				r.alert( 'Warning', 'Field URLs can not be empty' , 'roar_warning' );
				return false;
			}
			var obj = new Generate($('urls'));
		});
		
	});
	
	var Generate = new Class({
		initialize: function(textarea){
			this.el = textarea;
			this.resEl = $('res');
			this.urls = textarea.value;
			this.view = {'regular':true, 'quotes': false, 'brackets': false};
			this.initTypeView(); 
			this.generateUrl();				
		},
		initTypeView:function(){ 
			if( $('regular').checked )
				this.view.regular = true;
			else this.view.regular = false;
					
			if( $('quotes').checked )
				this.view.quotes = true;
			else this.view.quotes = false;

			if( $('brackets').checked )
				this.view.brackets = true;
			else this.view.brackets = false;

			if (!$('brackets').checked && !$('quotes').checked && !$('regular').checked ){
				this.view.regular = true;
			}
		},
		getUrl:function(){
			var urls = new String(this.urls);
			urls = urls.replace(/\n/ig,'@');
			var arrUrls = this.explode('@',urls);
			var i=0;
			var arrRes = new Array();
			arrUrls.each( function( s ){
				if( s ) {
					arrRes[i] = s.replace( 'www.', '' );
					i++;
				}
			});
			return arrRes;
		}, 
		explode: function( delimiter, string ) {    
    		return string.toString().split ( delimiter.toString() );
		},
		generateUrl: function(){
			var strRes = '';	
			var URLs = this.getUrl();
			if( this.view.regular ) {
				strRes += this.print(URLs,'','');
			}
			if( this.view.quotes ) {
				strRes += this.print(URLs,'"','"');
			}
			if( this.view.brackets ) {
				strRes += this.print(URLs,'[',']');
			}
			this.resEl.value = strRes; 
			$('field_res').style.display='block';	
		},
		print: function(URLs,left,right){
			var strRes = '';		
			URLs.each(function(value){
				var str = new String(value);
				match = str.match(/(.*)\.(.*)/i);
				if( !match ) {
					match = str.match(/(.*)/i);
				}
				if( match ) {
					var name = match[1];
					if( !match[2] ) { match[2] = '' }
					var domen = match[2];
					strRes = strRes + left + value + right + '\n';
					strRes = strRes + left +'www.' + value + right +'\n'; 
					strRes = strRes + left + name + ' ' + domen + right +'\n';
					strRes = strRes + left +'www ' + name + ' ' + domen + right +'\n';
					strRes = strRes + left + '' + name + '' + domen + right +'\n';
					strRes = strRes + left + 'www' + name + '' + domen + right +'\n';
				}
			});	
			return strRes;		
		}
	}); 
{/literal}	
</script>
