var bannerForm = new Class.create();
var bannercount=$$('.option-box .border tbody tr').length;
bannerForm.prototype = {
    initialize: function(contannerid) {
        this.contanner       = $(contannerid);
        if (!this.contanner) {
            return;
        }
        this.addutm	 = $('add_banner_group');
        $(this.addutm).observe('click', this.addtitle.bind(this,this.contanner));
        var i;
        for(i=0;i<$$('.option-box').length;i++){
        	$$('.option-box .option-header button')[i].observe('click',this.deltitle.bind(this,$$('.option-box .option-header button')[i].parentNode.parentNode.parentNode.parentNode.parentNode));
        	$$('.option-box .option-header button')[i].observe('click',this.getKey.bind(this,this.contanner));
        	$$('.option-box .border .add')[i].observe('click',this.addBanner.bind(this,$$('.option-box .border .add')[i].parentNode.parentNode.parentNode.parentNode));
        	$$('.option-box .border .add')[i].observe('click',this.getKey.bind(this,this.contanner));
        	$$('.option-box .title')[i].observe('change', this.getKey.bind(this,this.contanner));
        }
        for(i=0;i<$$('.option-box .border tbody tr').length;i++){
        	$$('.option-box .border .delete')[i].observe('click',this.delBanner.bind(this,$$('.option-box .border .delete')[i].parentNode.parentNode.parentNode.parentNode));
        	$$('.option-box .border .delete')[i].observe('click',this.getKey.bind(this,this.contanner));
        	$$('.option-box .border .utm_medium')[i].observe('change', this.getKey.bind(this,this.contanner));
        	$$('.option-box .border .utm_campain')[i].observe('change', this.getKey.bind(this,this.contanner));
        	$$('.option-box .border .url')[i].observe('change', this.getKey.bind(this,this.contanner));
        	$$('.option-box .border .utm_term')[i].observe('change', this.getKey.bind(this,this.contanner));
        	$$('.option-box .border .utm_source')[i].observe('change', this.getKey.bind(this,this.contanner));
        	$$('.option-box .border .file_upload')[i].observe('change', this.getKey.bind(this,this.contanner));
            $$('.option-box .border .file_upload_m')[i].observe('change', this.getKey.bind(this,this.contanner));
        	$$('.option-box .border select')[i].observe('change', this.getKey.bind(this,this.contanner));
        }
    },
	
	addtitle: function(contanner,event){
		var div= new Element("div",{"class":"option-box"});
		var table1 = new Element("table",{'style':'width:100%',"class":"option-header"});
		var table2 = new Element("table",{'style':'width:100%',"class":"border"});
		var input = new Element('input',{'type':'text',name:"title",'class':'title','style':'width:100px'});
		var addbtn=new Element('button',{'class':"add",'type':'button'}).insert(new Element('span').update('Add banner'));
		var delbtn=new Element('button',{'class':"delete",'type':'button'}).insert(new Element('span').update('Del'));
		input.observe('change', this.getKey.bind(this,this.contanner));
		addbtn.observe('click', this.addBanner.bind(this,table2));
        delbtn.observe('click', this.deltitle.bind(this,div));
        contanner.insert(div.insert(table1.insert(new Element('thead').insert(new Element('tr',{'class':'headings'}).insert(new Element('th',{'class':'opt-title'}).update('Title')).insert(new Element('th',{'class':'a-right'}).update('Action')))).insert(new Element('tbody').insert(new Element('tr').insert(new Element('td').insert(input)).insert(new Element('td',{'class':'a-right'}).insert(delbtn))))).insert(table2.insert(new Element('thead').insert(new Element('tr',{'class':'headings'}).insert(new Element('th').update('utm medium')).insert(new Element('th').update('utm campaign')).insert(new Element('th').update('utm term')).insert(new Element('th').update('utm source')).insert(new Element('th').update('Desktop banner')).insert(new Element('th').update('Mobile banner')).insert(new Element('th').update('url')).insert(new Element('th').update('Status')).insert(new Element('th').update('Action')))).insert(new Element('tbody')).insert(new Element('tfoot').insert(new Element('tr').insert(new Element('td',{'class':'a-right','colspan':'8'}).insert(addbtn))))));
	},
	
	deltitle: function(contanner,event) {
		contanner.remove();
	},
	
    addBanner: function(contanner,event) {
    	bannercount++;
        var delbtn=new Element('button',{type:"button",'class':"delete"}).insert(new Element('span').update('Del'));
        var input=new Element('input',{name:"utm_medium",'class':'utm_medium','style':'width:70px'});
        var input1=new Element('input',{name:"utm_campain",'class':'utm_campain','style':'width:70px'});
        var input2=new Element('input',{name:"utm_term",'class':'utm_term','style':'width:70px'});
        var input3=new Element('input',{name:"utm_source",'class':'utm_source','style':'width:70px'});
        var input4=new Element('input',{name:"url",'class':'url','style':'width:70px'});
        var file=new Element('input',{type:'file','class':'file_upload',name:"banner["+bannercount+"]"});
        var file_m=new Element('input',{type:'file','class':'file_upload_m',name:"banner_m["+bannercount+"]"});
        var select=new Element('select',{'style':'width:70px'}).insert(new Element('option',{'value':'1'}).update('enable')).insert(new Element('option',{'value':'0'}).update('disable'));
        delbtn.observe('click', this.delBanner.bind(this,contanner));
        input.observe('change', this.getKey.bind(this,this.contanner));
        input1.observe('change', this.getKey.bind(this,this.contanner));
        input2.observe('change', this.getKey.bind(this,this.contanner));
        input3.observe('change', this.getKey.bind(this,this.contanner));
        input4.observe('change', this.getKey.bind(this,this.contanner));
        file.observe('change', this.getKey.bind(this,this.contanner));
        file_m.observe('change', this.getKey.bind(this,this.contanner));
        
        select.observe('change', this.getKey.bind(this,this.contanner));
        contanner.tBodies[0].insert(new Element('tr').insert(new Element('td').insert(input)).insert(new Element('td').insert(input1)).insert(new Element('td').insert(input2)).insert(new Element('td').insert(input3)).insert(new Element('td').insert(file)).insert(new Element('td').insert(file_m)).insert(new Element('td').insert(input4)).insert(new Element('td').insert(select)).insert(new Element('td').insert(delbtn)));
    },
    
    delBanner: function(contanner,event) {
        var element = Event.element(event);
        do{
        	element=element.parentElement;
        }
        while(element.nodeName!="TR");
        element.remove();
    },
    
    getKey: function(contanner,event){
    	var i,j,filename,m_filename,string='';
    	for(i=0;i<$$('.option-box').length;i++){
    		if($$('.title')[i].value!='' && $$('.option-box .border')[i].tBodies[0].childElementCount!=0){
    			for(j=0;j<$$('.option-box .border')[i].tBodies[0].childElementCount;j++){
    				if($$('.option-box .border')[i].tBodies[0].children[j].children[4].children[0].value!=''){
    					if($$('.option-box .border')[i].tBodies[0].children[j].children[4].childElementCount==1)
    						filename=$$('.option-box .border')[i].tBodies[0].children[j].children[4].children[0].value;
    					else if($$('.option-box .border')[i].tBodies[0].children[j].children[4].children[1].value!='')
    						filename=$$('.option-box .border')[i].tBodies[0].children[j].children[4].children[1].value;
    					else{
    						filename=$$('.option-box .border')[i].tBodies[0].children[j].children[4].children[0].src;
    						filename=filename.substr(filename.lastIndexOf('/')+1);
    					}
    					if(filename.search("fakepath")!=1)
    						filename=filename.replace("C:\\fakepath\\", "");


                        if($$('.option-box .border')[i].tBodies[0].children[j].children[5].childElementCount==1)
                            m_filename=$$('.option-box .border')[i].tBodies[0].children[j].children[5].children[0].value;
                        else if($$('.option-box .border')[i].tBodies[0].children[j].children[5].children[1].value!='')
                            m_filename=$$('.option-box .border')[i].tBodies[0].children[j].children[5].children[1].value;
                        else{
                            m_filename=$$('.option-box .border')[i].tBodies[0].children[j].children[5].children[0].src;
                            m_filename=m_filename.substr(m_filename.lastIndexOf('/')+1);
                        }
                        if(filename.search("fakepath")!=1)
                            filename=filename.replace("C:\\fakepath\\", "");

						string += $$('.title')[i].value +','
							+$$('.option-box .border')[i].tBodies[0].children[j].children[0].children[0].value+','
							+$$('.option-box .border')[i].tBodies[0].children[j].children[1].children[0].value+','
							+$$('.option-box .border')[i].tBodies[0].children[j].children[2].children[0].value+','
							+$$('.option-box .border')[i].tBodies[0].children[j].children[3].children[0].value+','
							+filename+','+m_filename+','
							+$$('.option-box .border')[i].tBodies[0].children[j].children[6].children[0].value+','
							+$$('.option-box .border')[i].tBodies[0].children[j].children[7].children[0].value+',';
    				}
    			}
    		}
    	}
    	$('hidden_key').value=string.substr(0,string.length-1);
    }
}

