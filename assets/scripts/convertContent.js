
function convertContent(content) {
	content = marks('b');
	content = marks('i');
	content = marks('u');
	content = marks('code');
	content = emoji();
	//content = links('https://');
	//content = links('http://');
	
	function marks(mark) {
		do
		{
			content_temp=content;
			if(content.includes("[/"+mark+"]") && content.includes("["+mark+"]")) {
					if(content.indexOf("[/"+mark+"]")<content.indexOf("["+mark+"]")) {
						content = content.replace("[/"+mark+"]","");
						continue;
					} 
			} 
				content = content.replace("["+mark+"]","<#"+mark+">");
				if(content_temp!=content) {
					if(content.includes("[/"+mark+"]") && content.includes("["+mark+"]")) {
						if(content.indexOf("["+mark+"]")<content.indexOf("[/"+mark+"]")) {
							content = content.replace("<#"+mark+">","");
						} else {
							content_temp_2 = content;
							content = content.replace("[/"+mark+"]","</"+mark+">");
							content = content.replace("<#"+mark+">","<"+mark+">");			
						}
					} else if(content.includes("[/"+mark+"]")){
							content_temp_2 = content;
							content = content.replace("[/"+mark+"]","</"+mark+">");
							content = content.replace("<#"+mark+">","<"+mark+">");			
					} else {
						content = content.replace("<#"+mark+">","");	
					}
				} else {
						content = content.replace("[/"+mark+"]","");
				}
		}while(content_temp!=content);
		return content;
	}
	
	function emoji() {
		var index,emoji_true,emoji;
		do
		{
			content_temp=content;
			if(content.includes("U+")) {
				index = content.indexOf("U+");
				if(content[index+2]=='1' && content[index+3]=='F'){
					emoji_true="&#x1F";
					emoji="U+1F";
					for(var i=4;i<=6;i++)
					{
						emoji_true+=content[index+i];
						emoji+=content[index+i];
					}
					emoji_true+=";";
					content=content.replace(emoji,emoji_true);
				} else {
					emoji_true="<span class='emoji'>&#x";
					emoji="U+";
					for(var i=2;i<=5;i++)
					{
						emoji_true+=content[index+i];
						emoji+=content[index+i];
					}
					emoji_true+=";</span>";
					content=content.replace(emoji,emoji_true);
				}
			}
		}while(content_temp!=content);
	return content;
	}
	/*
	function links(start) {
		
		var index=0;
		while(content.includes(start,index)){
			var link='';
			index=content.indexOf(start,index);
			var i =0;
			while(content[index+i]!=" ")
			{
				link+=content[index+i];
				i++;
			}
			link_2="<a target='_blank' href='"+link+"'>"+link+"</a>";
			console.log(link);
			console.log(link_2);
			//content=content.replace(link,link_2);
			index+=35;
			console.log(index);
		}
		return content;
	}
	
	//document.getElementById('divek').innerHTML = content;
	*/
	return content;
}



