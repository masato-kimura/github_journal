<?php if (isset($error_message)):?>
<div class="container" style="margin: 18px auto;">
	<div class="alert alert-danger"><?php echo $error_message;?></div>
</div>
<?php else:?>
      <script type="text/javascript">
      jQuery(function() {
		var sendAjax = function(params) {
			return $.ajax({
				type: 'get',
				url: '<?php echo \Config::get('journal.www_host');?>/translation/ajax.json?q=' + params.q,
				datatype: 'json',
				//data: JSON.stringify(params),
				cache: false,
				success: function(res, ans) {
					if (res.success === false) {
						return false;
					}
				},
				error: function() {
					alert('ネットワークエラーが発生しました');
					return true;
				}
			});
		};

		$('#translation-submit-btn').on('click', function() {
			var params = {};
			params['q']    = $('#input-text').val().replace(/\n/ig, '@ret@');

			if ($('#input-text').length > 0) {
				sendAjax(params).done(function(res) {
					$('#output-text').val(res.text.replace(/(@ret@)/gi, '\n'));
					$('#output-text').trigger('autoresize');
					$('#output-text').focus();
					$('#translation-submit-btn').focus();
				});
			}
		});

		$('button[type=reset]').on('click', function() {
			$('html').focus();
		});

      });
      </script>
      <style type="text/css">
      	textarea {
      		font-size: 17px;
      	}
      </style>

	<nav>
		<div class="nav-wrapper">
			<div class="container">
				<a href="#!" class="brand-logo white-text" style="margin-left: -18px;"><i class="material-icons prefix">donut_small</i>Translation App</a>
			</div>
		</div>
	</nav>

	<div class="right blue-grey-text text-lighten-3" style="margin-top:3px; margin-right: 3px; font-size: small">Ver1.03 / LastUpd 03-11-2017</div>

    <div class="container">

      	<br />

      	<div class="row">
	      	<form class="col s12">
				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">mode_edit</i>
						<textarea id="input-text" class="materialize-textarea" style="font-size: 16px;"></textarea>
						<label for="input-text">日本語入力 Enter Japanese</label>
					</div>
				</div>
				<div class="row">
					<div class="col s12 center">
						<button class="btn-large waves-effect waves-light" type="button" name="action" id="translation-submit-btn">ローマ字へ変換<i class="material-icons right">translate</i></button>
						<button class="btn-large waves-effect waves-light grey lighten-1" type="reset" name="reset_action"><i class="material-icons">clear</i></button>
					</div>
				</div>

				<br />
				<br />

				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">content_copy</i>
						<textarea id="output-text" class="materialize-textarea" style="font-size: 16px;"></textarea>
						<label for="output-text">ローマ字出力 Output Romaji</label>
					</div>
				</div>
			</form>
		</div>
    </div>

   <footer class="page-footer">
		<div class="container">
			<div class="row">
				<div class="col l6 s12">
					<h5 class="white-text">日本語->ローマ字変換アプリ</h5>
					<p class="grey-text text-lighten-4">You can convert from Japanese to Romaji.<br />Did you like it? Mr.Yasu Ishihara</p>
				</div>
				<div class="col l4 offset-l2 s12">
					<h5 class="white-text">Thanks for APIs</h5>
					<ul>
						<li><a href="https://labs.goo.ne.jp/api/" target="_new" class="grey-text text-lighten-3">gooラボ</a></li>
						<li><a href="http://www.kawa.net/xp/index-e.html" target="_new" class="grey-text text-lighten-3">Kawa.net xp</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="footer-copyright">
			<div class="container">
			Copyright © Masato Kimura All Rights Reserved.
			</div>
		</div>
    </footer>


    </body>
  </html>
  <?php endif;?>