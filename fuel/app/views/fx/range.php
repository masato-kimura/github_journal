<?php if (isset($error_message)):?>
<div class="container" style="margin: 18px auto;">
	<div class="alert alert-danger"><?php echo $error_message;?></div>
</div>
<?php else:?>
      <script type="text/javascript">
      jQuery(function() {
    	  Materialize.updateTextFields();
    	  var lot  = 1;
    	  var fund = 100000;
    	  var volatility = 0;
          var max    = 0;
          var min    = 0;
          var middle_line = 0;
          var upper_line  = 0;
          var lower_line  = 0;
          var quadrant    = 0;
          var division    = 0;
          var six_division  = 0;
          var rieki_range = 0;
          var upper_cut_line = 0;
          var lower_cut_line = 0;
          var risk = '';
          var lost_cut = 0;

          var init_cookie = function() {
              // ロット数
              if ($.cookie('lot') == undefined) {
                  lot = $('#lot').val();
                  $.cookie('lot', lot);
              } else {
                  lot = $.cookie('lot');
                  $('#lot').val(lot);
              }
              $('#lot').on('change', function() {
                  lot = $('#lot').val();
                  $.cookie('lot', lot);
              });
              // 資金
              if ($.cookie('fund') == undefined) {
                  fund = $('#fund').val();
                  $.cookie('fund', fund);
              } else {
                  fund = $.cookie('fund');
                  $('#fund').val(fund);
              }
              $('#fund').on('change', function() {
                  fund = $('#fund').val();
                  $.cookie('fund', fund);
              });
          };

          var get_volatility = function() {
              volatility = max - min;
              var volatility_for_disp = volatility * 100;
              var volatility_price = parseInt(volatility * 10000 * lot, 10);
              volatility_for_disp = volatility_for_disp.toFixed(3) + 'pips (' + volatility_price + '円)';
              $('#volatility').val(volatility_for_disp);
              return true;
          };

          var get_middle_line = function() {
              middle_line = volatility/2 + min;
              var middle_line_for_disp = middle_line.toFixed(3);
              $('#middle_line').val(middle_line_for_disp);
              return true;
          };

          var get_entry_position = function() {
              quadrant = volatility/4;
              division = volatility/5;
              six_division = volatility/6;
              upper_line = max - quadrant;
              lower_line  = min + quadrant;

              var upper_line_for_disp = upper_line.toFixed(3);
              var lower_line_for_disp = lower_line.toFixed(3);
              var quadrant_for_disp = quadrant * 100;
              quadrant_for_disp = quadrant_for_disp.toFixed(2)
              var quadrant_price = quadrant * 10000 * lot;
              quadrant_price = parseInt(quadrant_price);
              quadrant_for_disp =  quadrant_for_disp + 'pips (' +  quadrant_price + '円)';

              $('#upper_line').val(upper_line_for_disp);
              $('#lower_line').val(lower_line_for_disp);
              $('#quadrant_pips').val(quadrant_for_disp);

              rieki_range = upper_line - lower_line;
              var rieki_range_for_disp = rieki_range * 100;
              rieki_range_for_disp = rieki_range_for_disp.toFixed(2);
              var rieki_price = rieki_range * 10000 * lot;
              rieki_price = parseInt(rieki_price);
              rieki_range_for_disp = rieki_range_for_disp + 'pips (' + rieki_price + '円)';
              $('#rieki_range').val(rieki_range_for_disp);

              lost_cut = division;
              upper_cut_line = max + lost_cut;
              var upper_cut_line_for_disp = upper_cut_line.toFixed(3);
              $('#upper_cut_line').val(upper_cut_line_for_disp);
              lower_cut_line = min - lost_cut;
              var lower_cut_line_for_disp = lower_cut_line.toFixed(3);
              $('#lower_cut_line').val(lower_cut_line_for_disp);

              var lost_cut_for_disp = lost_cut * 100;
              var lost_cut_price    = lost_cut * 10000 * lot;
              lost_cut_price = parseInt(lost_cut_price);
              lost_cut_for_disp = lost_cut_for_disp.toFixed(2) + 'pips (' + lost_cut_price + '円)';
              $('#lost_cut').val(lost_cut_for_disp);

              var lost_pips = lost_cut * 100 + quadrant * 100;
              var lost_price = lost_cut_price + quadrant_price;
              lost_pips = lost_pips.toFixed(3) + 'pips (' + lost_price + '円)';
              $('#lost_pips').val(lost_pips);

              var risk_1 = fund * 0.01;
              var risk_2 = fund * 0.02;
              risk_1 = parseInt(risk_1);
              rist_2 = parseInt(risk_2);
              risk = risk_1 + '～' + risk_2 + '円';
              $('#risk').val(risk);

              var upper_cut_1_per = max + ((fund * 0.01)/10000)/lot;
              upper_cut_1_per = upper_cut_1_per.toFixed(3);
              var upper_cut_2_per = max + ((fund * 0.02)/10000)/lot;
              upper_cut_2_per = upper_cut_2_per.toFixed(3);
              var lower_cut_1_per = min - ((fund * 0.01)/10000)/lot;
              lower_cut_1_per = lower_cut_1_per.toFixed(3);
              var lower_cut_2_per = min - ((fund * 0.02)/10000)/lot;
              lower_cut_2_per = lower_cut_2_per.toFixed(3);

              $('#upper_cut_1_per').val(upper_cut_1_per);
              $('#upper_cut_2_per').val(upper_cut_2_per);
              $('#lower_cut_1_per').val(lower_cut_1_per);
              $('#lower_cut_2_per').val(lower_cut_2_per);

          };

          init_cookie();

          $('button[type=reset]').on('click', function() {
              $('.reset_on').val('');
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
              max = parseFloat(max, 10);
              min = parseFloat(min, 10);
              if (max == 0 || min == 0) {
                return true;
              };

              // ボラティリティ取得
              get_volatility();
              get_middle_line();

              // エントリーポジション取得
              get_entry_position();

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
				<a href="#!" class="brand-logo white-text" style="margin-left: -18px;"><i class="material-icons prefix">insert_photo</i>FX App レンジ計算</a>
			</div>
		</div>
	</nav>

	<div class="right blue-grey-text text-lighten-3" style="margin-top:3px; margin-right: 3px; font-size: small">Ver1.00 / LastUpd 07-18-2017</div>

    <div class="container">

      	<br />
      	<br />

      	<div class="row">
	      	<form class="col s12">
		      	<div class="row">
					<div class="col s6">
						<label for="lot">ロット数 (1lot=10,000通貨)</label>
						<select id="lot" name="lot">
						<?php for ($i=1; $i<=100; $i++):?>
						<option value="<?php echo $i;?>"><?php echo $i;?></option>
						<?php endfor; ?>
						</select>
					</div>
					<div class="col s6">
						<label for="fund">資金</label>
						<input type="number" id="fund" name="fund">
					</div>
				</div>

				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">expand_less</i>
						<input type="number" id="input-max-lange" class="reset_on" style="font-size: 16px;">
						<label for="input-max-lange">最大レンジ</label>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">expand_more</i>
						<input type="number" id="input-min-lange" class="reset_on" style="font-size: 16px;">
						<label for="input-min-lange">最小レンジ</label>
					</div>
				</div>

				<div class="row">
					<div class="col s12 center">
						<button class="btn-large waves-effect waves-light" type="button" name="action" id="fx-submit-btn">計算実行<i class="material-icons">insert_chart</i></button>
						<button class="btn-large waves-effect waves-light grey lighten-1" type="reset" name="reset_action"><i class="material-icons">clear</i></button>
					</div>
				</div>

				<br />
				<br />

				<div class="row">
					<div class="input-field col s6">
						<input type="number" id="upper_line" class="reset_on" placeholder="">
						<label for="upper_line">上位ライン<br />（指値）</label>
					</div>
					<div class="input-field col s6">
						<input type="number" id="lower_line" class="reset_on" placeholder="">
						<label for="lower_line">下位ライン<br />（指値）</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<input type="text" id="rieki_range" class="reset_on" placeholder="">
						<label for="rieki_range">利益幅</label>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s6">
						<input type="number" id="upper_cut_line" class="reset_on" placeholder="">
						<label for="upper_cut_line">上位損切ライン<span id="upper_cut_pips"></span></label>
					</div>
					<div class="input-field col s6">
						<input type="number" id="lower_cut_line" class="reset_on" placeholder="">
						<label for="lower_cut_line">下位損切ライン</label>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s6">
						<input type="text" id="lost_cut" class="reset_on" placeholder="">
						<label for="lost_cut">ロストカット値 (1/5pips)</label>
					</div>
					<div class="input-field col s6">
						<input type="text" id="quadrant_pips" class="reset_on" placeholder="">
						<label for="quadrant_pips">マージン幅(1/4pips)</label>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s12">
						<input type="text" id="lost_pips" class="reset_on" placeholder="">
						<label for="lost_pips">損切り幅 (ロストカット+マージン)</label>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s12">
						<input type="text" id="risk" class="reset_on" placeholder="">
						<label for="risk">許容リスク (資金の1~2%)</label>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s6">
						<input type="text" id="upper_cut_1_per" class="reset_on" placeholder="">
						<label for="upper_cut_1_per">上値カット（上限1%）</label>
					</div>
					<div class="input-field col s6">
						<input type="text" id="upper_cut_2_per" class="reset_on" placeholder="">
						<label for="upper_cut_2_per">上値カット（上限2%）</label>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s6">
						<input type="text" id="lower_cut_1_per" class="reset_on" placeholder="">
						<label for="lower_cut_1_per">下値カット（上限1%）</label>
					</div>
					<div class="input-field col s6">
						<input type="text" id="lower_cut_2_per" class="reset_on" placeholder="">
						<label for="lower_cut_2_per">下値カット（上限2%）</label>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s8">
						<input type="text" id="volatility" class="reset_on" placeholder="">
						<label for="volatility">ボラティリティ(pips)</label>
					</div>
					<div class="input-field col s4">
						<input type="number" id="middle_line" class="reset_on" placeholder="">
						<label for="middle_line">中心値</label>
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