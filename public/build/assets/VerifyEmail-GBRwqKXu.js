import{T as c,e as f,f as p,w as i,o as n,g as o,h as t,Z as g,b as a,c as y,k as b,m as r,n as _,l as v,i as x}from"./app-fJPKEAaS.js";import{_ as k}from"./GuestLayout.vue_vue_type_script_setup_true_lang-BqeLq1OY.js";import{P as w}from"./PrimaryButton-B2AMTrAh.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const h={key:0,class:"mb-4 font-medium text-sm text-green-600"},V={class:"mt-4 flex items-center justify-between"},T={__name:"VerifyEmail",props:{status:{type:String}},setup(l){const d=l,s=c({}),m=()=>{s.post(route("verification.send"))},u=f(()=>d.status==="verification-link-sent");return(B,e)=>(n(),p(k,null,{default:i(()=>[o(t(g),{title:"Email Verification"}),e[2]||(e[2]=a("div",{class:"mb-4 text-sm text-gray-600"}," Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another. ",-1)),u.value?(n(),y("div",h," A new verification link has been sent to the email address you provided during registration. ")):b("",!0),a("form",{onSubmit:x(m,["prevent"])},[a("div",V,[o(w,{class:_({"opacity-25":t(s).processing}),disabled:t(s).processing},{default:i(()=>e[0]||(e[0]=[r(" Resend Verification Email ")])),_:1},8,["class","disabled"])]),o(t(v),{method:"post",as:"button",class:"btn btn-gradient !mt-6 w-full border-0 uppercase shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]"},{default:i(()=>e[1]||(e[1]=[r(" Log Out ")])),_:1})],32)]),_:1}))}};export{T as default};
