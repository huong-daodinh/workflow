import{d as n,c as t,t as s,b as r,o as a}from"./app-fd2yWfTJ.js";const c={class:"flex gap-1 items-center"},l=["src"],o={key:1,class:"flex justify-center items-center w-6 h-6 text-center rounded-full text-sm bg-primary text-white font-bold"},i={class:"ml-2 text-base text-black font-bold"},f=n({__name:"UserInfo",props:{userData:{type:Object,required:!0}},setup(e){return(u,m)=>(a(),t("div",c,[e.userData.avatar?(a(),t("img",{key:0,src:e.userData.avatar,alt:"avatar",class:"inline-block h-6 w-6 rounded-full"},null,8,l)):(a(),t("span",o,s(e.userData.name.split(" ")[0][0]+e.userData.name.split(" ")[e.userData.name.split(" ").length-1][0]),1)),r("span",i,s(e.userData.name),1)]))}});export{f as _};