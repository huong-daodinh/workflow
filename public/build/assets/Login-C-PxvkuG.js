import{d as x,T as b,f as n,w as d,o as i,g as r,h as a,Z as _,c as w,t as h,k as m,b as e,i as v,j as u,v as p,l as k,m as y}from"./app-fJPKEAaS.js";import{_ as E}from"./GuestLayout.vue_vue_type_script_setup_true_lang-BqeLq1OY.js";import{_ as V}from"./icon-mail.vue_vue_type_script_setup_true_lang-BKmqX8mh.js";import{_ as B}from"./icon-lock-dots.vue_vue_type_script_setup_true_lang-By3rE_Dp.js";import{_ as f}from"./InputError-iKmV-a6W.js";const N={key:0,class:"mb-4 font-medium text-sm text-green-600"},P={class:"relative flex min-h-screen items-center justify-center px-6 py-10 dark:bg-[#060818] sm:px-16"},j={class:"relative w-full max-w-[870px] rounded-md bg-[linear-gradient(45deg,#fff9f9_0%,rgba(255,255,255,0)_25%,rgba(255,255,255,0)_75%,_#fff9f9_100%)] p-2 dark:bg-[linear-gradient(52.22deg,#0E1726_0%,rgba(14,23,38,0)_18.66%,rgba(14,23,38,0)_51.04%,rgba(14,23,38,0)_80.07%,#0E1726_100%)]"},$={class:"relative flex flex-col justify-center rounded-md bg-white/60 backdrop-blur-lg dark:bg-black/50 px-6 lg:min-h-[758px] py-20"},C={class:"mx-auto w-full max-w-[440px]"},M={class:"relative text-white-dark"},S={class:"absolute start-4 top-1/2 -translate-y-1/2"},T={class:"relative text-white-dark"},D={class:"absolute start-4 top-1/2 -translate-y-1/2"},Q=x({__name:"Login",props:{canResetPassword:{type:Boolean},status:{type:String}},setup(l){const t=b({email:"",password:"",remember:!1}),g=()=>{t.post(route("login"),{onFinish:()=>t.reset("password")})};return(c,s)=>(i(),n(E,null,{default:d(()=>[r(a(_),{title:"Log in"}),l.status?(i(),w("div",N,h(l.status),1)):m("",!0),e("div",null,[s[7]||(s[7]=e("div",{class:"absolute inset-0"},[e("img",{src:"/assets/images/auth/bg-gradient.png",alt:"image",class:"h-full w-full object-cover"})],-1)),e("div",P,[e("div",j,[e("div",$,[e("div",C,[s[6]||(s[6]=e("div",{class:"mb-16"},[e("h1",{class:"text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl"}," Đăng nhập "),e("p",{class:"text-base font-bold leading-normal text-white-dark"}," Nhập email và mật khẩu để đăng nhập ")],-1)),e("form",{class:"space-y-5 dark:text-white",onSubmit:v(g,["prevent"])},[e("div",null,[s[2]||(s[2]=e("label",{for:"Email"},"Email",-1)),e("div",M,[u(e("input",{id:"Email","onUpdate:modelValue":s[0]||(s[0]=o=>a(t).email=o),placeholder:"Enter Email",class:"form-input ps-10 placeholder:text-white-dark"},null,512),[[p,a(t).email]]),e("span",S,[r(V,{fill:!0})])]),r(f,{message:a(t).errors.email,class:"mt-2"},null,8,["message"])]),e("div",null,[s[3]||(s[3]=e("label",{for:"Password"},"Mật khẩu",-1)),e("div",T,[u(e("input",{id:"Password","onUpdate:modelValue":s[1]||(s[1]=o=>a(t).password=o),type:"password",placeholder:"Enter Password",class:"form-input ps-10 placeholder:text-white-dark"},null,512),[[p,a(t).password]]),e("span",D,[r(B,{fill:!0})])]),r(f,{message:a(t).errors.password,class:"mt-2"},null,8,["message"])]),e("div",null,[l.canResetPassword?(i(),n(a(k),{key:0,href:c.route("password.request"),class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:d(()=>s[4]||(s[4]=[y(" Quên mật khẩu? ")])),_:1},8,["href"])):m("",!0)]),s[5]||(s[5]=e("button",{type:"submit",class:"btn btn-gradient !mt-6 w-full border-0 uppercase shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]"}," Đăng nhập ",-1))],32)])])])])])]),_:1}))}});export{Q as default};
