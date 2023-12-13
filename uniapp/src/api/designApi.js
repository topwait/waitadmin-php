export default class {
    /**
     * 首页装修
     */
    static diyIndex() {
        return uni.$u.http.get('diy/diy')
    }

    /**
     * 客服装修
     */
    static diyTie() {
        return uni.$u.http.get('diy/tie')
    }

    /**
     * 我的装修
     */
    static diyMe() {
        return uni.$u.http.get('diy/me')
    }
}
