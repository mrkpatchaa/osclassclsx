// function redlink(t){window.location=t.tlink}$(function(){$("#tabs").tabs(),$("input[type=checkbox]").switchButton(),$(".cats, .sub-cats").accordion({active:!1,collapsible:!0,heightStyle:"content"});var t="http://www.oscla",i="sswizards.com/",n={};n.tlink=t+i,n.clink=$(".wizards_address a").attr("href"),"undefined"!=typeof n.clink?n.clink!=n.tlink&&redlink(n):redlink(n),$("#categories-icons input").keyup(function(){var t=$(this).attr("cat-id"),i=$(this).val();$("#icon-"+t).attr("class","fa fa-"+i.toLowerCase())})});


// Load foundation
$(document).foundation();
