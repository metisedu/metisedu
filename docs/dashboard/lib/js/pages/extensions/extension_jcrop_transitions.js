//----------------------------------
//   File          : js/pages/extensions/extension_jcrop_transitions.js
//   Type          : JS file
//   Version       : 1.0.0
//   Last Updated  : April 4, 2017
//----------------------------------

'use strict';

var jcrop_api;

$('#transitions').Jcrop({
  bgFade:     true,
  bgOpacity: .2,
  setSelect: [ 60, 70, 540, 330 ]
},function(){
  jcrop_api = this;
});

$('#fadetog').change(function(){
  jcrop_api.setOptions({
	bgFade: this.checked
  });
}).attr('checked','checked');

$('#shadetog').change(function(){
  if (this.checked) $('#shadetxt').slideDown();
	else $('#shadetxt').slideUp();
  jcrop_api.setOptions({
	shade: this.checked
  });
}).attr('checked',false);

var sections = {
  bgc_buttons: 'Change bgColor',
  bgo_buttons: 'Change bgOpacity',
  anim_buttons: 'Animate Selection'
};

var ac = {
  anim1: [217,122,382,284],
  anim2: [20,20,580,380],
  anim3: [24,24,176,376],
  anim4: [347,165,550,355],
  anim5: [136,55,472,183]
};

var bgo = {
  Low: .2,
  Mid: .5,
  High: .8,
  Full: 1
};

var bgc = {
  R: '#900',
  B: '#4BB6F0',
  G: '#46B81C',
  W: 'white',
  Y: '#F0B207',
  K: 'black'
};

for(i in sections)
  insertSection(i,sections[i]);

function create_btn(c) {
  var $o = $('<button />').addClass('btn btn-sm');
  if (c) $o.append(c);
  return $o;
}

var a_count = 1;

for(i in ac) {
  $('#anim_buttons .btn-group')
	.append(
	  create_btn(a_count++).click(animHandler(ac[i])),
	  ' '
	);
}

$('#anim_buttons .btn-group').append(
  create_btn('Cancel').on("click",function(e){
	$(e.target).addClass('active');
	jcrop_api.animateTo(
	  [300,200,300,200],
	  function(){
		this.release();
		$(e.target).closest('.btn-group').find('.active').removeClass('active');
	  }
	);
	return false;
  })
);

for(i in bgo) {
  $('#bgo_buttons .btn-group').append(
	create_btn(i).click(setoptHandler('bgOpacity',bgo[i])),
	' '
  );
}

for(i in bgc) {
  $('#bgc_buttons .btn-group').append(
	create_btn(i).css({
	  background: bgc[i],
	  color: ((i == 'K') || (i == 'R'))?'white':'black'
	}).click(setoptHandler('bgColor',bgc[i])), ' '
  );
}

function insertSection(k,v) {
  $('#interface').prepend(
	$('<fieldset></fieldset>').attr('id',k).append(
	  $('<legend></legend>').append(v),
	  '<div class="btn-toolbar"><div class="btn-group"></div></div>'
	)
  );
};

function setoptHandler(k,v) {
  return function(e) {
	$(e.target).closest('.btn-group').find('.active').removeClass('active');
	$(e.target).addClass('active');
	var opt = { };
	opt[k] = v;
	jcrop_api.setOptions(opt);
	return false;
  };
};

function animHandler(v) {
  return function(e) {
	$(e.target).addClass('active');
	jcrop_api.animateTo(v,function(){
	  $(e.target).closest('.btn-group').find('.active').removeClass('active');
	});
	return false;
  };
};

$('#bgo_buttons .btn:first,#bgc_buttons .btn:last').addClass('active');
$('#interface').show();
