<template>
        <div class="w-full  h-full  bg-opacity-90 top-0 overflow-y-auto overflow-x-hidden  sticky-0" id="chec-div">
            <div class="w-full absolute z-10 right-0 h-full overflow-x-hidden transform translate-x-0 transition ease-in-out duration-700" id="notification">
                <div class="2xl:w-4/12 bg-gray-50 h-screen overflow-y-auto p-4 mx-auto">
                    <div v-for="notification in notifications">
                    <div v-if="notification.type=='like'" :class="['w-full p-3 mt-4  rounded shadow flex flex-shrink-0',notification.status ? 'bg-white' : 'bg-sky-200']">
                        <div tabindex="0" aria-label="heart icon" role="img" class="focus:outline-none w-8 h-8 bg-white border rounded-full border-gray-200 flex items-center justify-center">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.00059 3.01934C9.56659 1.61334 11.9866 1.66 13.4953 3.17134C15.0033 4.68334 15.0553 7.09133 13.6526 8.662L7.99926 14.3233L2.34726 8.662C0.944589 7.09133 0.997256 4.67934 2.50459 3.17134C4.01459 1.662 6.42992 1.61134 8.00059 3.01934Z" fill="#EF4444" />
                            </svg>
                        </div>
                        <div class="pl-3">
                            <p tabindex="0" class="focus:outline-none text-sm leading-none"><router-link :to="{name:'user.show', params:{
                                id:notification.user.id
                            }}"><span class="text-indigo-700">{{notification.user.name}}</span></router-link> поставил лайк вашему посту с названием <span class="text-indigo-700">"{{notification.title}}"</span></p>
                            <p tabindex="0" class="focus:outline-none text-xs leading-3 pt-1 text-gray-500">{{notification.time}}</p>
                        </div>
                    </div>
                    <div v-if="notification.type=='subscribe'" :class="['w-full p-3 mt-4  rounded shadow flex flex-shrink-0',notification.status ? 'bg-white' : 'bg-sky-200']">
                        <div tabindex="0" aria-label="group icon" role="img" class="focus:outline-none bg-white w-8 h-8 border rounded-full border-gray-200 flex flex-shrink-0 items-center justify-center">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1.33325 14.6667C1.33325 13.2522 1.89516 11.8956 2.89535 10.8954C3.89554 9.89523 5.2521 9.33333 6.66659 9.33333C8.08107 9.33333 9.43763 9.89523 10.4378 10.8954C11.438 11.8956 11.9999 13.2522 11.9999 14.6667H1.33325ZM6.66659 8.66666C4.45659 8.66666 2.66659 6.87666 2.66659 4.66666C2.66659 2.45666 4.45659 0.666664 6.66659 0.666664C8.87659 0.666664 10.6666 2.45666 10.6666 4.66666C10.6666 6.87666 8.87659 8.66666 6.66659 8.66666ZM11.5753 10.1553C12.595 10.4174 13.5061 10.9946 14.1788 11.8046C14.8515 12.6145 15.2515 13.6161 15.3219 14.6667H13.3333C13.3333 12.9267 12.6666 11.3427 11.5753 10.1553ZM10.2266 8.638C10.7852 8.13831 11.232 7.52622 11.5376 6.84183C11.8432 6.15743 12.0008 5.41619 11.9999 4.66666C12.0013 3.75564 11.7683 2.85958 11.3233 2.06466C12.0783 2.21639 12.7576 2.62491 13.2456 3.2208C13.7335 3.81668 14.0001 4.56315 13.9999 5.33333C14.0001 5.80831 13.8987 6.27784 13.7027 6.71045C13.5066 7.14306 13.2203 7.52876 12.863 7.84169C12.5056 8.15463 12.0856 8.38757 11.6309 8.52491C11.1762 8.66224 10.6974 8.7008 10.2266 8.638Z"
                                    fill="#047857"
                                />
                            </svg>
                        </div>
                        <div class="pl-3 w-full">
                            <div class="flex items-center justify-between w-full">
                                <p tabindex="0" class="focus:outline-none text-sm leading-none"><router-link :to="{name:'user.show', params:{
                                id:notification.user.id
                            }}"><span class="text-indigo-700">{{notification.user.name}}</span></router-link> подписался на вас!</p>
                            </div>
                            <p tabindex="0" class="focus:outline-none text-xs leading-3 pt-1 text-gray-500">{{notification.time}}</p>
                        </div>
                    </div>
                    <div v-if="notification.type=='comment'" :class="['w-full p-3 mt-4  rounded shadow flex flex-shrink-0',notification.status ? 'bg-white' : 'bg-sky-200']">
                        <div tabindex="0" aria-label="post icon" role="img" class="focus:outline-none bg-white w-8 h-8 border rounded-full border-gray-200 flex items-center justify-center">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.30325 12.6667L1.33325 15V2.66667C1.33325 2.48986 1.40349 2.32029 1.52851 2.19526C1.65354 2.07024 1.82311 2 1.99992 2H13.9999C14.1767 2 14.3463 2.07024 14.4713 2.19526C14.5963 2.32029 14.6666 2.48986 14.6666 2.66667V12C14.6666 12.1768 14.5963 12.3464 14.4713 12.4714C14.3463 12.5964 14.1767 12.6667 13.9999 12.6667H4.30325ZM5.33325 6.66667V8H10.6666V6.66667H5.33325Z" fill="#4338CA" />
                            </svg>
                        </div>
                        <div class="pl-3">
                            <p tabindex="0" class="focus:outline-none text-sm leading-none"><span class="text-indigo-700">Sarah</span> posted in the thread: <span class="text-indigo-700">Update gone wrong</span></p>
                            <p tabindex="0" class="focus:outline-none text-xs leading-3 pt-1 text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                        <div v-if="notification.type=='repost'" :class="['w-full p-3 mt-4  rounded shadow flex flex-shrink-0',notification.status ? 'bg-white' : 'bg-sky-200']">
                            <div tabindex="0" aria-label="post icon" role="img" class="focus:outline-none w-8 h-8 bg-white border rounded-full border-gray-200 flex items-center justify-center">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    :class="[
              'mx-2 stroke-sky-500 cursor-pointer fill-sky-500 w-8 h-8 ',
            ]"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"
                                    />
                                </svg>
                            </div>
                            <div class="pl-3">
                                <p tabindex="0" class="focus:outline-none text-sm leading-none"><span class="text-indigo-700">{{notification.user.name}}</span><span> сделал репост вашего поста с заголовком </span><span class="text-indigo-700">"{{notification.title}}"</span></p>
                                <p tabindex="0" class="focus:outline-none text-xs leading-3 pt-1 text-gray-500">{{notification.time}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>
<script>
export default{
    data(){
        return {
notifications:[]
        };
    },
async mounted(){
    await this.getNotifications();
    this.checkNotification();
},
     methods:{
getNotifications(){
axios.get('/api/user/notifications')
    .then(res=>{
        this.notifications=res.data.data
        console.log(this.notifications)
    })
},
checkNotification(){
        axios.get("/api/user/notifications/check").then((res) => {
        this.countNotification= 0;
      });
    }
     }
}
</script>
