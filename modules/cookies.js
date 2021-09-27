import DefCookie from 'default-cookies';



export default class Cookie extends DefCookie {
  constructor(name, value, maxAge = null) {
    super(name, value, '/colori', maxAge);
  }

  static delete(name) {
    super.delete('/colori', name);
  }
}