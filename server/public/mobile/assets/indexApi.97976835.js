class t{static index(){return uni.$u.http.get("index/index")}static config(){return uni.$u.http.get("index/config")}static policy(t){return uni.$u.http.get("index/policy",{type:t})}static sendSms(t){const e={scene:t.scene,mobile:t.mobile};return uni.$u.http.post("index/sendSms",e)}static sendEmail(t){const e={scene:t.scene,email:t.email};return uni.$u.http.post("index/sendEmail",e)}}export{t as i};