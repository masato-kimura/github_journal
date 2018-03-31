<?php if (isset($error_message)):?>
<div class="container" style="margin: 18px auto;">
	<div class="alert alert-danger"><?php echo $error_message;?></div>
</div>
<?php else:?>
      <script type="text/javascript">
      jQuery(function() {
    	  Materialize.updateTextFields();
          var max    = 0;
          var min    = 0;
          var middle = 0;
          var real_lange = 0;
          var lange  = 0;
          var lange_top = 0;
          var lange_bottom = 0;
          var max_position = 0;
          var min_position = 0;
          var position_trigger_per = 0; // 有効レンジ
          var position_lost_per    = 0; // 損切％（有効レンジ幅と比較して）
          var position_break_per   = 0; // ブレイクポイント設定％（有効レンジ幅と比較して）
          var direction_per = -10; // 上昇および下落率　+-10％
          var margin = 0; // 実値からレンジ値までの差分
          var lange_lost = 0;
          var lange_break = 0;
          var get_support_per = 0; // 利益確定まで早める％
          var max_lost  = 0;
          var min_lost  = 0;
          var max_get   = 0;
          var min_get   = 0;
          var max_break = 0;
          var min_break = 0;
          var max_break_lost = 0;
          var min_break_lost = 0;
          var max_break_get  = 0;
          var min_break_get  = 0;

          var init_cookie = function() {
              // レンジ幅
              if ($.cookie('position_trigger_per') == undefined) {
                  position_trigger_per = $('#position_trigger_per').val();
                  $.cookie('position_trigger_per', position_trigger_per);
              } else {
                  position_trigger_per = $.cookie('position_trigger_per');
                  $('#position_trigger_per').val(position_trigger_per);
              }
              $('#position_trigger_per').on('change', function() {
                  position_trigger_per = $('#position_trigger_per').val();
                  $.cookie('position_trigger_per', position_trigger_per);
              });

              // 損切％
              if ($.cookie('position_lost_per') == undefined) {
                  position_lost_per = $('#position_lost_per').val();
                  $.cookie('position_lost_per', position_lost_per);
              } else {
                  position_lost_per = $.cookie('position_lost_per');
                  $('#position_lost_per').val(position_lost_per);
              }
              $('#position_lost_per').on('change', function() {
                  position_lost_per = $('#position_lost_per').val();
                  $.cookie('position_lost_per', position_lost_per);
              });

              // ブレイクポイント発生％
              if ($.cookie('position_break_per') == undefined) {
                  position_break_per = $('#position_break_per').val();
                  $.cookie('position_break_per', position_break_per);
              } else {
                  position_break_per = $.cookie('position_break_per');
                  $('#position_break_per').val(position_break_per);
              }
              $('#position_break_per').on('change', function() {
                  position_break_per = $('#position_break_per').val();
                  $.cookie('position_break_per', position_break_per);
              });

              // 上昇下落率
              if ($.cookie('direction_per') == undefined) {
                  direction_per = $('#direction_per').val();
                  $.cookie('direction_per', direction_per);
              } else {
            	  direction_per = $.cookie('direction_per');
                  $('#direction_per').val(direction_per);
              }
              $('#direction_per').on('change', function() {
            	  direction_per = $('#direction_per').val();
                  $.cookie('direction_per', direction_per);
              });
          };

          var get_lange = function() {
              real_lange  = max - min;
              lange  = real_lange * position_trigger_per/100;
              middle = real_lange/2 + min;
              margin = (real_lange - lange)/2;
              return true;
          };
          var get_entry_position = function() {
              lange_lost  = lange * position_lost_per/100;
              lange_break = lange * position_break_per/100;

              max_position_org = max - margin;
              max_position     = max_position_org.toFixed(3);
              min_position_org = min + margin;
              min_position     = min_position_org.toFixed(3);

              max_lost_org  = max_position_org + lange_lost;
              max_lost      = max_lost_org.toFixed(3);
              min_lost_org  = min_position_org - lange_lost;
              min_lost      = min_lost_org.toFixed(3);

              max_get_org = min_position_org + (min_position_org * 0.00016);
              max_get = max_get_org.toFixed(3);
              min_get_org = max_position_org - (max_position_org * 0.00016);
              min_get = min_get_org.toFixed(3);

              max_break_org = max_position_org + lange_break;
              max_break     = max_break_org.toFixed(3);

              min_break_org = min_position_org - lange_break;
              min_break     = min_break_org.toFixed(3);

              var lange_per = lange/3;
              max_break_get_org = max_break_org + lange_per
              max_break_get     = max_break_get_org.toFixed(3);
              min_break_get_org = min_break_org - lange_per;
              min_break_get     = min_break_get_org.toFixed(3);

              max_break_lost_org = max_break_org - (lange_per);
              max_break_lost = max_break_lost_org.toFixed(3);
              min_break_lost_org = min_break_org + (lange_per);
              min_break_lost = min_break_lost_org.toFixed(3);
          };

          var adjust = function(val) {
              if (direction_per == "0") {
                  return val;
              }
              var direction_per_int = parseInt(direction_per)/100;
              var direction_per_abs = Math.abs(direction_per_int);
              var val_int = val + ''; // string型に変換
              val_int = parseInt(val_int.replace(/[\.]/, ''));
              var move = val_int * (direction_per_abs/100);
              if (direction_per_int > 0) {
                  val_int = val_int + move;
              } else {
                  val_int = val_int - move;
              }
              val_int = parseInt(val_int) + '';
              val_int = val_int.replace(/^([\d]{3})([\d]+)/, "$1.$2");
              return parseFloat(val_int);
          };

           init_cookie();

          $('button[type=reset]').on('click', function() {
              $('input[type=number]').val('');
              return false;
          });

          $('#fx-submit-btn').on('click', function() {
              max = $('#input-max-lange').val().replace(/[^\d\.]/g, '');
              min = $('#input-min-lange').val().replace(/[^\d\.]/g, '');

              if (max.length == 0) {
                  max = 0;
              };
              if (min.length == 0) {
                  min = 0;
              };
              max = adjust(parseFloat(max, 10));
              min = adjust(parseFloat(min, 10));
              if (max == 0 || min == 0) {
                return true;
              };

              // 中心値, レンジ取得
              get_lange();
              // エントリーポジション取得
              get_entry_position();

              $('#output-max-position').val(max_position);
              $('#output-max-lost').val(max_lost);
              $('#output-max-get').val(max_get);
              $('#output-max-break').val(max_break);
              $('#output-max-break-lost').val(max_break_lost);
              $('#output-max-break-get').val(max_break_get);
              $('#output-min-position').val(min_position);
              $('#output-min-lost').val(min_lost);
              $('#output-min-get').val(min_get);
              $('#output-min-break').val(min_break);
              $('#output-min-break-lost').val(min_break_lost);
              $('#output-min-break-get').val(min_break_get);
          });
      });
      </script>
      <style type="text/css">
        nav, footer.page-footer {
          background: #000;
        }
        select {
          display: inline;
          font-size: 16px;
        }

      </style>

	<nav>
		<div class="nav-wrapper">
			<div class="container">
				<a href="#!" class="brand-logo white-text" style="margin-left: -18px;"><i class="material-icons prefix">insert_photo</i>FX App</a>
			</div>
		</div>
	</nav>

	<div class="right blue-grey-text text-lighten-3" style="margin-top:3px; margin-right: 3px; font-size: small">Ver1.00 / LastUpd 05-29-2017</div>

    <div class="container">

      	<br />
      	<br />

      	<div class="row">
	      	<form class="col s12">
		      	<div class="row">
					<div class="col s6">
						<label for="position_trigger_per">有効レンジ幅設定<br />（エントリポイント）</label>
						<select id="position_trigger_per" name="position_trigger_per">
						<?php for ($i=60; $i<=100; $i++):?>
							<?php if ($i == 72):?>
						<option value="<?php echo $i;?>" selected><?php echo $i;?>%</option>
							<?php else: ?>
						<option value="<?php echo $i;?>"><?php echo $i;?>%</option>
							<?php endif;?>
						<?php endfor; ?>
						</select>
					</div>
					<div class="col s6">
						<label for="direction_per">上昇下落率<br />(+-20%)</label>
						<select id="direction_per" name="direction_per">
						<?php for ($i=20; $i>=-20; $i--):?>
							<?php if ($i==0):?>
								<option value="<?php echo $i;?>" selected><?php echo $i;?>%</option>
							<?php else:?>
								<option value="<?php echo $i;?>"><?php echo $i;?>%</option>
							<?php endif;?>
						<?php endfor; ?>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col s6">
						<label for="position_lost_per">損切り設定<br />(有効レンジ幅との比較)</label>
						<select id="position_lost_per" name="position_lost_per">
						<?php for ($i=0; $i<=50; $i++):?>
							<?php if ($i == 25):?>
								<option value="<?php echo $i;?>" selected><?php echo $i;?>%</option>
							<?php else:?>
								<option value="<?php echo $i;?>"><?php echo $i;?>%</option>
							<?php endif;?>
						<?php endfor; ?>
						</select>
					</div>
					<div class="col s6">
						<label for="position_break_per">ブレイクポイント設定<br />(有効レンジ幅との比較)</label>
						<select id="position_break_per" name="position_break_per">
						<?php for ($i=0; $i<=50; $i++):?>
							<?php if ($i == 30):?>
								<option value="<?php echo $i;?>" selected><?php echo $i;?>%</option>
							<?php else:?>
								<option value="<?php echo $i;?>"><?php echo $i;?>%</option>
							<?php endif;?>
						<?php endfor; ?>
						</select>
					</div>
		      	</div>

				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">expand_less</i>
						<input type="number" id="input-max-lange" style="font-size: 16px;">
						<label for="input-max-lange">最大レンジ</label>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">expand_more</i>
						<input type="number" id="input-min-lange" style="font-size: 16px;">
						<label for="input-min-lange">最小レンジ</label>
					</div>
				</div>

				<div class="row">
					<div class="col s12 center">
						<button class="btn-large waves-effect waves-light" type="button" name="action" id="fx-submit-btn">IFDOCO注文パターン<i class="material-icons">insert_chart</i></button>
						<button class="btn-large waves-effect waves-light grey lighten-1" type="reset" name="reset_action"><i class="material-icons">clear</i></button>
					</div>
				</div>

				<br />
				<br />

				<div class="row">
					<div class="input-field col s4">
						<input type="number" id="output-max-break" placeholder="">
						<label for="output-max-break">上位ブレイク買い<br />（逆指値）</label>
					</div>
					<div class="input-field col s4">
						<input type="number" id="output-max-break-get" placeholder="">
						<label for="output-max-break-get">利切り売り<br />（指値）</label>
					</div>
					<div class="input-field col s4">
						<input type="number" id="output-max-break-lost" placeholder="">
						<label for="output-max-break-lost">損切り売り<br />（逆指値）</label>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s4">
						<input type="number" id="output-max-position" placeholder="">
						<label for="output-max-position">上位売り<br />（指値）</label>
					</div>
					<div class="input-field col s4">
						<input type="number" id="output-max-get" placeholder="">
						<label for="output-max-get">利切り買い<br />（指値）</label>
					</div>
					<div class="input-field col s4">
						<input type="number" id="output-max-lost" placeholder="">
						<label for="output-max-lost">損切り買い<br />（逆指値）</label>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s4">
						<input type="number" id="output-min-position" placeholder="">
						<label for="output-min-position">下位買い<br />（指値）</label>
					</div>
					<div class="input-field col s4">
						<input type="number" id="output-min-get" placeholder="">
						<label for="output-min-get">利切り売り<br />（指値）</label>
					</div>
					<div class="input-field col s4">
						<input type="number" id="output-min-lost" placeholder="">
						<label for="output-min-lost">損切り売り<br />（逆指値）</label>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s4">
						<input type="number" id="output-min-break" placeholder="">
						<label for="output-min-break">下位ブレイク売り<br />（逆指値）</label>
					</div>
					<div class="input-field col s4">
						<input type="number" id="output-min-break-get" placeholder="">
						<label for="output-min-break-get">利切り買い<br />（指値）</label>
					</div>
					<div class="input-field col s4">
						<input type="number" id="output-min-break-lost" placeholder="">
						<label for="output-min-break-lost">損切り買い<br />（逆指値）</label>
					</div>
				</div>


			</form>
		</div>
    </div>

   <footer class="page-footer">
		<div class="container">
			<div class="row">
				<div class="col l6 s12">
					<h5 class="white-text">FXレンジ計算アプリ</h5>
					<p class="grey-text text-lighten-4"></p>
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