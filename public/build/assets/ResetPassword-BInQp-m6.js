import{T as c,f as _,w as m,o as w,g as r,h as a,Z as g,b as s,i as x,j as o,v as i,m as b,n as v}from"./app-fJPKEAaS.js";import{_ as h}from"./GuestLayout.vue_vue_type_script_setup_true_lang-BqeLq1OY.js";import{_ as d}from"./InputError-iKmV-a6W.js";import{P as k}from"./PrimaryButton-B2AMTrAh.js";import{_ as p}from"./icon-lock-dots.vue_vue_type_script_setup_true_lang-By3rE_Dp.js";import{_ as y}from"./icon-mail.vue_vue_type_script_setup_true_lang-BKmqX8mh.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const P={class:"relative flex min-h-screen items-center justify-center px-6 py-10 dark:bg-[#060818] sm:px-16"},E={class:"relative w-full max-w-[870px] rounded-md bg-[linear-gradient(45deg,#fff9f9_0%,rgba(255,255,255,0)_25%,rgba(255,255,255,0)_75%,_#fff9f9_100%)] p-2 dark:bg-[linear-gradient(52.22deg,#0E1726_0%,rgba(14,23,38,0)_18.66%,rgba(14,23,38,0)_51.04%,rgba(14,23,38,0)_80.07%,#0E1726_100%)]"},V={class:"relative flex flex-col justify-center rounded-md bg-white/60 backdrop-blur-lg dark:bg-black/50 px-6 lg:min-h-[758px] py-20"},j={class:"mx-auto w-full max-w-[440px]"},B={class:"relative text-white-dark"},$={class:"absolute start-4 top-1/2 -translate-y-1/2"},M={class:"relative text-white-dark"},N={class:"absolute start-4 top-1/2 -translate-y-1/2"},S={class:"relative text-white-dark"},T={class:"absolute start-4 top-1/2 -translate-y-1/2"},U={class:"flex items-center justify-end mt-4"},A={__name:"ResetPassword",props:{email:{type:String,required:!0},token:{type:String,required:!0}},setup(u){const n=u,e=c({token:n.token,email:n.email,password:"",password_confirmation:""}),f=()=>{e.post(route("password.store"),{onFinish:()=>e.reset("password","password_confirmation")})};return(q,t)=>(w(),_(h,null,{default:m(()=>[r(a(g),{title:"Reset Password"}),s("div",null,[t[8]||(t[8]=s("div",{class:"absolute inset-0"},[s("img",{src:"/assets/images/auth/bg-gradient.png",alt:"image",class:"h-full w-full object-cover"})],-1)),s("div",P,[s("div",E,[s("div",V,[s("div",j,[t[7]||(t[7]=s("div",{class:"mb-16"},[s("h1",{class:"text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl"}," Đặt lại mật khẩu ")],-1)),s("form",{onSubmit:x(f,["prevent"]),class:"space-y-5"},[s("div",null,[t[3]||(t[3]=s("label",{for:"Email"},"Email",-1)),s("div",B,[o(s("input",{id:"Email","onUpdate:modelValue":t[0]||(t[0]=l=>a(e).email=l),placeholder:"Enter Email",class:"form-input ps-10 placeholder:text-white-dark"},null,512),[[i,a(e).email]]),s("span",$,[r(y,{fill:!0})])]),r(d,{message:a(e).errors.email,class:"mt-2"},null,8,["message"])]),s("div",null,[t[4]||(t[4]=s("label",{for:"Password"},"Mật khẩu",-1)),s("div",M,[o(s("input",{id:"Password","onUpdate:modelValue":t[1]||(t[1]=l=>a(e).password=l),type:"password",placeholder:"Enter Password",class:"form-input ps-10 placeholder:text-white-dark"},null,512),[[i,a(e).password]]),s("span",N,[r(p,{fill:!0})])]),r(d,{message:a(e).errors.password,class:"mt-2"},null,8,["message"])]),s("div",null,[t[5]||(t[5]=s("label",{for:"Password"},"Xác nhận mật khẩu",-1)),s("div",S,[o(s("input",{id:"Password","onUpdate:modelValue":t[2]||(t[2]=l=>a(e).password_confirmation=l),type:"password",placeholder:"Enter Password",class:"form-input ps-10 placeholder:text-white-dark"},null,512),[[i,a(e).password_confirmation]]),s("span",T,[r(p,{fill:!0})])]),r(d,{message:a(e).errors.password_confirmation,class:"mt-2"},null,8,["message"])]),s("div",U,[r(k,{class:v({"opacity-25":a(e).processing}),disabled:a(e).processing},{default:m(()=>t[6]||(t[6]=[b(" Đặt lại ")])),_:1},8,["class","disabled"])])],32)])])])])])]),_:1}))}};export{A as default};
