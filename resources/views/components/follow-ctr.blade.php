@props(['user'])

<div x-data="{
    following: {{ auth()->check() && $user->isFollowedBy(auth()->user()) ? 'true' : 'false' }},
    followersCount: {{ $user->followers()->count() }},
    follow(){
        this.following = !this.following
        axios.post('/follow/{{ $user->username }}')
        .then(res => {
            console.log(res.data)
            this.followersCount = res.data.followersCount
            
            // Broadcast event để sync với các component khác
            window.dispatchEvent(new CustomEvent('user-follow-updated', {
                detail: {
                    userId: {{ $user->id }},
                    following: this.following,
                    followersCount: res.data.followersCount
                }
            }))
        })
        .catch(err => {
            console.log(err)
            this.following = !this.following  // Revert nếu lỗi
        })  
    },
    init() {
        // Listen for updates from other components
        window.addEventListener('user-follow-updated', (event) => {
            if (event.detail.userId === {{ $user->id }}) {
                this.following = event.detail.following
                this.followersCount = event.detail.followersCount
            }
        })
    }
}"
class="w-[320px] border-l px-8">
{{ $slot }}
</div>