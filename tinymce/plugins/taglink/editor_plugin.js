(function() {
	var JSONRequest = tinymce.util.JSONRequest, each = tinymce.each, DOM = tinymce.DOM;
        tinymce.PluginManager.requireLangPack('taglink');
        tinymce.create('tinymce.plugins.TagLinkPlugin', {
                getInfo : function() {
                        return {
                                longname : 'Tag, Category & Author Link Button',
                                author : 'Keith McDuffee',
                                authorurl : 'http://gudlyf.com',
                                infourl : 'http://gudlyf.com',
                                version : '1.0'
                        };
                },

                init : function(ed, url) {
			var t = this, cm;

			t.url = url;
			t.editor = ed;
			t.bimg = url + '/img/button.gif'
                },

                createControl : function(n, cm) {
			var t = this;
			var ed = t.editor;
			switch(n) {
				case 'taglink' :
					var c = cm.createSplitButton('taglink', {
						title : 'TagLink',
						image : t.bimg,
						onclick : function() {
				var tag = ed.selection.getContent({format : 'text'});
				tag = tag.toLowerCase();
				tag = tag.replace(/\s+$/,'');
				tag = tag.replace(/^\s+/,'');
				tag = tag.replace(/[^a-zA-Z 0-9]+/g,'');
				tag = tag.replace(/\s/g,'-');
				ed.execCommand('CreateLink',false,getTagLink(tag));
						}
					});

					c.onRenderMenu.add(function(c,m) {
						writer = m.addMenu({title : ' Writers'});

						JSONRequest.sendRPC({
							url : t.url+'/getwriters.php',
							method : 'getSuggestions',
							params : null,
							success : function(r) {
                                                        	each(r, function(v) {
		writer.add({title : v['display_name'], onclick : function(){
			var con = ed.selection.getContent();
			var author_link = getAuthorLink(v['user_login']);
			if(con == '')
				ed.selection.setContent('<a href=\"'+author_link+'\" title=\"'+v['display_name']+'\">'+v['display_name']+'</a>');
			else
				ed.selection.setContent('<a href=\"'+author_link+'\" title=\"'+v['display_name']+'\">'+con+'</a>');
		} });
								});
							},
							error : function(e, x) {
		alert(e.errstr || ('Error response: ' + x.responseText));
							}
						});
						cat1 = m.addMenu({title : ' Categories (# - L)'});

						JSONRequest.sendRPC({
							url : t.url+'/getcats.php?col=1',
							method : 'getSuggestions',
							params : null,
							success : function(r) {
                                                        	each(r, function(v) {
		cat1.add({title : v['cat_name'], onclick : function(){
			var con = ed.selection.getContent();
			var cat_url = getCatLink(v['cat_ID']);
			if(con == '')
				ed.selection.setContent('<a href=\"'+cat_url+'\" title=\"'+v['category_description']+'\">'+v['cat_name']+'</a>');
			else
				ed.selection.setContent('<a href=\"'+cat_url+'\" title=\"'+v['category_description']+'\">'+con+'</a>');
		} });
								});
							},
							error : function(e, x) {
		alert(e.errstr || ('Error response: ' + x.responseText));
							}
						});
						cat2 = m.addMenu({title : ' Categories (M - Z)'});

						JSONRequest.sendRPC({
							url : t.url+'/getcats.php?col=2',
							method : 'getSuggestions',
							params : null,
							success : function(r) {
                                                        	each(r, function(v) {
		cat2.add({title : v['cat_name'], onclick : function(){
			var con = ed.selection.getContent();
			var cat_url = getCatLink(v['cat_ID']);
			if(con == '')
				ed.selection.setContent('<a href=\"'+cat_url+'\" title=\"'+v['category_description']+'\">'+v['cat_name']+'</a>');
			else
				ed.selection.setContent('<a href=\"'+cat_url+'\" title=\"'+v['category_description']+'\">'+con+'</a>');
		} });
								});
							},
							error : function(e, x) {
		alert(e.errstr || ('Error response: ' + x.responseText));
							}
						});

					});
				return c;
			}
			return null;
		},
	});
        tinymce.PluginManager.add('taglink', tinymce.plugins.TagLinkPlugin);
})();
