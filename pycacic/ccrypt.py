# -*- coding: UTF-8 -*-

"""

    Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil
    
    Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais
    
    O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como 
    publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.
    
    Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
    MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.
    
    Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a Fundação do Software
    Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
    

    Modulo ccrypt (Cacic Crypt)
    
    Modulo com finalidade de encriptar ou decriptar
    as mensagens enviadas ou recebidas pelo Gerente Web.
    E tambem as guardadas nos arquivos locais (temporarios ou nao)
    
    Tornando mais segura a comunicacao entre o agente e o servidor
    
    @author: Dataprev - ES
    @version: 1.0.0 (14 de abril de 2008)
    
"""

import re
import binascii
import base64
import random
import array
import math

"""cryptomath module

This module has basic math/crypto code."""

import os
import math
import base64
import binascii
#import sha
import hashlib

#from compat import *


# **************************************************************************
# Load Optional Modules
# **************************************************************************

# Try to load M2Crypto/OpenSSL
try:
    from M2Crypto import m2
    m2cryptoLoaded = 1

except ImportError:
    m2cryptoLoaded = 0


# Try to load cryptlib
try:
    import cryptlib_py
    try:
        cryptlib_py.cryptInit()
    except cryptlib_py.CryptException, e:
        #If tlslite and cryptoIDlib are both present,
        #they might each try to re-initialize this,
        #so we're tolerant of that.
        if e[0] != cryptlib_py.CRYPT_ERROR_INITED:
            raise
    cryptlibpyLoaded = 1

except ImportError:
    cryptlibpyLoaded = 0

#Try to load GMPY
try:
    import gmpy
    gmpyLoaded = 1
except ImportError:
    gmpyLoaded = 0

#Try to load pycrypto
try:
    import Crypto.Cipher.AES
    pycryptoLoaded = 1
except ImportError:
    pycryptoLoaded = 0


# **************************************************************************
# PRNG Functions
# **************************************************************************

# Get os.urandom PRNG
try:
    os.urandom(1)
    def getRandomBytes(howMany):
        return stringToBytes(os.urandom(howMany))
    prngName = "os.urandom"

except:
    # Else get cryptlib PRNG
    if cryptlibpyLoaded:
        def getRandomBytes(howMany):
            randomKey = cryptlib_py.cryptCreateContext(cryptlib_py.CRYPT_UNUSED,
                                                       cryptlib_py.CRYPT_ALGO_AES)
            cryptlib_py.cryptSetAttribute(randomKey,
                                          cryptlib_py.CRYPT_CTXINFO_MODE,
                                          cryptlib_py.CRYPT_MODE_OFB)
            cryptlib_py.cryptGenerateKey(randomKey)
            bytes = createByteArrayZeros(howMany)
            cryptlib_py.cryptEncrypt(randomKey, bytes)
            return bytes
        prngName = "cryptlib"

    else:
        #Else get UNIX /dev/urandom PRNG
        try:
            devRandomFile = open("/dev/urandom", "rb")
            def getRandomBytes(howMany):
                return stringToBytes(devRandomFile.read(howMany))
            prngName = "/dev/urandom"
        except IOError:
            #Else get Win32 CryptoAPI PRNG
            try:
                import win32prng
                def getRandomBytes(howMany):
                    s = win32prng.getRandomBytes(howMany)
                    if len(s) != howMany:
                        raise AssertionError()
                    return stringToBytes(s)
                prngName ="CryptoAPI"
            except ImportError:
                #Else no PRNG :-(
                def getRandomBytes(howMany):
                    raise NotImplementedError("No Random Number Generator "\
                                              "available.")
            prngName = "None"

# **************************************************************************
# Converter Functions
# **************************************************************************

def bytesToNumber(bytes):
    total = 0L
    multiplier = 1L
    for count in range(len(bytes)-1, -1, -1):
        byte = bytes[count]
        total += multiplier * byte
        multiplier *= 256
    return total

def numberToBytes(n):
    howManyBytes = numBytes(n)
    bytes = createByteArrayZeros(howManyBytes)
    for count in range(howManyBytes-1, -1, -1):
        bytes[count] = int(n % 256)
        n >>= 8
    return bytes

def bytesToBase64(bytes):
    s = bytesToString(bytes)
    return stringToBase64(s)

def base64ToBytes(s):
    s = base64ToString(s)
    return stringToBytes(s)

def numberToBase64(n):
    bytes = numberToBytes(n)
    return bytesToBase64(bytes)

def base64ToNumber(s):
    bytes = base64ToBytes(s)
    return bytesToNumber(bytes)

def stringToNumber(s):
    bytes = stringToBytes(s)
    return bytesToNumber(bytes)

def numberToString(s):
    bytes = numberToBytes(s)
    return bytesToString(bytes)

def base64ToString(s):
    try:
        return base64.decodestring(s)
    except binascii.Error, e:
        raise SyntaxError(e)
    except binascii.Incomplete, e:
        raise SyntaxError(e)

def stringToBase64(s):
    return base64.encodestring(s).replace("\n", "")

def mpiToNumber(mpi): #mpi is an openssl-format bignum string
    if (ord(mpi[4]) & 0x80) !=0: #Make sure this is a positive number
        raise AssertionError()
    bytes = stringToBytes(mpi[4:])
    return bytesToNumber(bytes)

def numberToMPI(n):
    bytes = numberToBytes(n)
    ext = 0
    #If the high-order bit is going to be set,
    #add an extra byte of zeros
    if (numBits(n) & 0x7)==0:
        ext = 1
    length = numBytes(n) + ext
    bytes = concatArrays(createByteArrayZeros(4+ext), bytes)
    bytes[0] = (length >> 24) & 0xFF
    bytes[1] = (length >> 16) & 0xFF
    bytes[2] = (length >> 8) & 0xFF
    bytes[3] = length & 0xFF
    return bytesToString(bytes)



# **************************************************************************
# Misc. Utility Functions
# **************************************************************************

def numBytes(n):
    if n==0:
        return 0
    bits = numBits(n)
    return int(math.ceil(bits / 8.0))

def hashAndBase64(s):
    return stringToBase64(sha.sha(s).digest())

def getBase64Nonce(numChars=22): #defaults to an 132 bit nonce
    bytes = getRandomBytes(numChars)
    bytesStr = "".join([chr(b) for b in bytes])
    return stringToBase64(bytesStr)[:numChars]


# **************************************************************************
# Big Number Math
# **************************************************************************

def getRandomNumber(low, high):
    if low >= high:
        raise AssertionError()
    howManyBits = numBits(high)
    howManyBytes = numBytes(high)
    lastBits = howManyBits % 8
    while 1:
        bytes = getRandomBytes(howManyBytes)
        if lastBits:
            bytes[0] = bytes[0] % (1 << lastBits)
        n = bytesToNumber(bytes)
        if n >= low and n < high:
            return n

def gcd(a,b):
    a, b = max(a,b), min(a,b)
    while b:
        a, b = b, a % b
    return a

def lcm(a, b):
    #This will break when python division changes, but we can't use // cause
    #of Jython
    return (a * b) / gcd(a, b)

#Returns inverse of a mod b, zero if none
#Uses Extended Euclidean Algorithm
def invMod(a, b):
    c, d = a, b
    uc, ud = 1, 0
    while c != 0:
        #This will break when python division changes, but we can't use //
        #cause of Jython
        q = d / c
        c, d = d-(q*c), c
        uc, ud = ud - (q * uc), uc
    if d == 1:
        return ud % b
    return 0


if gmpyLoaded:
    def powMod(base, power, modulus):
        base = gmpy.mpz(base)
        power = gmpy.mpz(power)
        modulus = gmpy.mpz(modulus)
        result = pow(base, power, modulus)
        return long(result)

else:
    #Copied from Bryan G. Olson's post to comp.lang.python
    #Does left-to-right instead of pow()'s right-to-left,
    #thus about 30% faster than the python built-in with small bases
    def powMod(base, power, modulus):
        nBitScan = 5

        """ Return base**power mod modulus, using multi bit scanning
        with nBitScan bits at a time."""

        #TREV - Added support for negative exponents
        negativeResult = 0
        if (power < 0):
            power *= -1
            negativeResult = 1

        exp2 = 2**nBitScan
        mask = exp2 - 1

        # Break power into a list of digits of nBitScan bits.
        # The list is recursive so easy to read in reverse direction.
        nibbles = None
        while power:
            nibbles = int(power & mask), nibbles
            power = power >> nBitScan

        # Make a table of powers of base up to 2**nBitScan - 1
        lowPowers = [1]
        for i in xrange(1, exp2):
            lowPowers.append((lowPowers[i-1] * base) % modulus)

        # To exponentiate by the first nibble, look it up in the table
        nib, nibbles = nibbles
        prod = lowPowers[nib]

        # For the rest, square nBitScan times, then multiply by
        # base^nibble
        while nibbles:
            nib, nibbles = nibbles
            for i in xrange(nBitScan):
                prod = (prod * prod) % modulus
            if nib: prod = (prod * lowPowers[nib]) % modulus

        #TREV - Added support for negative exponents
        if negativeResult:
            prodInv = invMod(prod, modulus)
            #Check to make sure the inverse is correct
            if (prod * prodInv) % modulus != 1:
                raise AssertionError()
            return prodInv
        return prod


#Pre-calculate a sieve of the ~100 primes < 1000:
def makeSieve(n):
    sieve = range(n)
    for count in range(2, int(math.sqrt(n))):
        if sieve[count] == 0:
            continue
        x = sieve[count] * 2
        while x < len(sieve):
            sieve[x] = 0
            x += sieve[count]
    sieve = [x for x in sieve[2:] if x]
    return sieve

sieve = makeSieve(1000)

def isPrime(n, iterations=5, display=0):
    #Trial division with sieve
    for x in sieve:
        if x >= n: return 1
        if n % x == 0: return 0
    #Passed trial division, proceed to Rabin-Miller
    #Rabin-Miller implemented per Ferguson & Schneier
    #Compute s, t for Rabin-Miller
    if display: print "*",
    s, t = n-1, 0
    while s % 2 == 0:
        s, t = s/2, t+1
    #Repeat Rabin-Miller x times
    a = 2 #Use 2 as a base for first iteration speedup, per HAC
    for count in range(iterations):
        v = powMod(a, s, n)
        if v==1:
            continue
        i = 0
        while v != n-1:
            if i == t-1:
                return 0
            else:
                v, i = powMod(v, 2, n), i+1
        a = getRandomNumber(2, n)
    return 1

def getRandomPrime(bits, display=0):
    if bits < 10:
        raise AssertionError()
    #The 1.5 ensures the 2 MSBs are set
    #Thus, when used for p,q in RSA, n will have its MSB set
    #
    #Since 30 is lcm(2,3,5), we'll set our test numbers to
    #29 % 30 and keep them there
    low = (2L ** (bits-1)) * 3/2
    high = 2L ** bits - 30
    p = getRandomNumber(low, high)
    p += 29 - (p % 30)
    while 1:
        if display: print ".",
        p += 30
        if p >= high:
            p = getRandomNumber(low, high)
            p += 29 - (p % 30)
        if isPrime(p, display=display):
            return p

#Unused at the moment...
def getRandomSafePrime(bits, display=0):
    if bits < 10:
        raise AssertionError()
    #The 1.5 ensures the 2 MSBs are set
    #Thus, when used for p,q in RSA, n will have its MSB set
    #
    #Since 30 is lcm(2,3,5), we'll set our test numbers to
    #29 % 30 and keep them there
    low = (2 ** (bits-2)) * 3/2
    high = (2 ** (bits-1)) - 30
    q = getRandomNumber(low, high)
    q += 29 - (q % 30)
    while 1:
        if display: print ".",
        q += 30
        if (q >= high):
            q = getRandomNumber(low, high)
            q += 29 - (q % 30)
        #Ideas from Tom Wu's SRP code
        #Do trial division on p and q before Rabin-Miller
        if isPrime(q, 0, display=display):
            p = (2 * q) + 1
            if isPrime(p, display=display):
                if isPrime(q, display=display):
                    return p


class AES:
    def __init__(self, key, mode, IV, implementation):
        if len(key) not in (16, 24, 32):
            raise AssertionError()
        if mode != 2:
            raise AssertionError()
        if len(IV) != 16:
            raise AssertionError()
        self.isBlockCipher = 1 # True
        self.block_size = 16
        self.implementation = implementation
        if len(key)==16:
            self.name = "aes128"
        elif len(key)==24:
            self.name = "aes192"
        elif len(key)==32:
            self.name = "aes256"
        else:
            raise AssertionError()

    #CBC-Mode encryption, returns ciphertext
    #WARNING: *MAY* modify the input as well
    def encrypt(self, plaintext):
        assert(len(plaintext) % 16 == 0)

    #CBC-Mode decryption, returns plaintext
    #WARNING: *MAY* modify the input as well
    def decrypt(self, ciphertext):
        assert(len(ciphertext) % 16 == 0)

"""
A pure python (slow) implementation of rijndael with a decent interface

To include -

from rijndael import rijndael

To do a key setup -

r = rijndael(key, block_size = 16)

key must be a string of length 16, 24, or 32
blocksize must be 16, 24, or 32. Default is 16

To use -

ciphertext = r.encrypt(plaintext)
plaintext = r.decrypt(ciphertext)

If any strings are of the wrong length a ValueError is thrown
"""

# ported from the Java reference code by Bram Cohen, bram@gawth.com, April 2001
# this code is public domain, unless someone makes
# an intellectual property claim against the reference
# code, in which case it can be made public domain by
# deleting all the comments and renaming all the variables

import copy
import string



#-----------------------
#TREV - ADDED BECAUSE THERE'S WARNINGS ABOUT INT OVERFLOW BEHAVIOR CHANGING IN
#2.4.....
import os
if os.name != "java":
    import exceptions
    if hasattr(exceptions, "FutureWarning"):
        import warnings
        warnings.filterwarnings("ignore", category=FutureWarning, append=1)
#-----------------------



shifts = [[[0, 0], [1, 3], [2, 2], [3, 1]],
          [[0, 0], [1, 5], [2, 4], [3, 3]],
          [[0, 0], [1, 7], [3, 5], [4, 4]]]

# [keysize][block_size]
num_rounds = {16: {16: 10, 24: 12, 32: 14}, 24: {16: 12, 24: 12, 32: 14}, 32: {16: 14, 24: 14, 32: 14}}

A = [[1, 1, 1, 1, 1, 0, 0, 0],
     [0, 1, 1, 1, 1, 1, 0, 0],
     [0, 0, 1, 1, 1, 1, 1, 0],
     [0, 0, 0, 1, 1, 1, 1, 1],
     [1, 0, 0, 0, 1, 1, 1, 1],
     [1, 1, 0, 0, 0, 1, 1, 1],
     [1, 1, 1, 0, 0, 0, 1, 1],
     [1, 1, 1, 1, 0, 0, 0, 1]]

# produce log and alog tables, needed for multiplying in the
# field GF(2^m) (generator = 3)
alog = [1]
for i in xrange(255):
    j = (alog[-1] << 1) ^ alog[-1]
    if j & 0x100 != 0:
        j ^= 0x11B
    alog.append(j)

log = [0] * 256
for i in xrange(1, 255):
    log[alog[i]] = i

# multiply two elements of GF(2^m)
def mul(a, b):
    if a == 0 or b == 0:
        return 0
    return alog[(log[a & 0xFF] + log[b & 0xFF]) % 255]

# substitution box based on F^{-1}(x)
box = [[0] * 8 for i in xrange(256)]
box[1][7] = 1
for i in xrange(2, 256):
    j = alog[255 - log[i]]
    for t in xrange(8):
        box[i][t] = (j >> (7 - t)) & 0x01

B = [0, 1, 1, 0, 0, 0, 1, 1]

# affine transform:  box[i] <- B + A*box[i]
cox = [[0] * 8 for i in xrange(256)]
for i in xrange(256):
    for t in xrange(8):
        cox[i][t] = B[t]
        for j in xrange(8):
            cox[i][t] ^= A[t][j] * box[i][j]

# S-boxes and inverse S-boxes
S =  [0] * 256
Si = [0] * 256
for i in xrange(256):
    S[i] = cox[i][0] << 7
    for t in xrange(1, 8):
        S[i] ^= cox[i][t] << (7-t)
    Si[S[i] & 0xFF] = i

# T-boxes
G = [[2, 1, 1, 3],
    [3, 2, 1, 1],
    [1, 3, 2, 1],
    [1, 1, 3, 2]]

AA = [[0] * 8 for i in xrange(4)]

for i in xrange(4):
    for j in xrange(4):
        AA[i][j] = G[i][j]
        AA[i][i+4] = 1

for i in xrange(4):
    pivot = AA[i][i]
    if pivot == 0:
        t = i + 1
        while AA[t][i] == 0 and t < 4:
            t += 1
            assert t != 4, 'G matrix must be invertible'
            for j in xrange(8):
                AA[i][j], AA[t][j] = AA[t][j], AA[i][j]
            pivot = AA[i][i]
    for j in xrange(8):
        if AA[i][j] != 0:
            AA[i][j] = alog[(255 + log[AA[i][j] & 0xFF] - log[pivot & 0xFF]) % 255]
    for t in xrange(4):
        if i != t:
            for j in xrange(i+1, 8):
                AA[t][j] ^= mul(AA[i][j], AA[t][i])
            AA[t][i] = 0

iG = [[0] * 4 for i in xrange(4)]

for i in xrange(4):
    for j in xrange(4):
        iG[i][j] = AA[i][j + 4]

def mul4(a, bs):
    if a == 0:
        return 0
    r = 0
    for b in bs:
        r <<= 8
        if b != 0:
            r = r | mul(a, b)
    return r

T1 = []
T2 = []
T3 = []
T4 = []
T5 = []
T6 = []
T7 = []
T8 = []
U1 = []
U2 = []
U3 = []
U4 = []

for t in xrange(256):
    s = S[t]
    T1.append(mul4(s, G[0]))
    T2.append(mul4(s, G[1]))
    T3.append(mul4(s, G[2]))
    T4.append(mul4(s, G[3]))

    s = Si[t]
    T5.append(mul4(s, iG[0]))
    T6.append(mul4(s, iG[1]))
    T7.append(mul4(s, iG[2]))
    T8.append(mul4(s, iG[3]))

    U1.append(mul4(t, iG[0]))
    U2.append(mul4(t, iG[1]))
    U3.append(mul4(t, iG[2]))
    U4.append(mul4(t, iG[3]))

# round constants
rcon = [1]
r = 1
for t in xrange(1, 30):
    r = mul(2, r)
    rcon.append(r)

del A
del AA
del pivot
del B
del G
del box
del log
del alog
del i
del j
del r
del s
del t
del mul
del mul4
del cox
del iG

class rijndael:
    def __init__(self, key, block_size = 16):
        if block_size != 16 and block_size != 24 and block_size != 32:
            raise ValueError('Invalid block size: ' + str(block_size))
        if len(key) != 16 and len(key) != 24 and len(key) != 32:
            raise ValueError('Invalid key size: ' + str(len(key)))
        self.block_size = block_size

        ROUNDS = num_rounds[len(key)][block_size]
        BC = block_size / 4
        # encryption round keys
        Ke = [[0] * BC for i in xrange(ROUNDS + 1)]
        # decryption round keys
        Kd = [[0] * BC for i in xrange(ROUNDS + 1)]
        ROUND_KEY_COUNT = (ROUNDS + 1) * BC
        KC = len(key) / 4

        # copy user material bytes into temporary ints
        tk = []
        for i in xrange(0, KC):
            tk.append((ord(key[i * 4]) << 24) | (ord(key[i * 4 + 1]) << 16) |
                (ord(key[i * 4 + 2]) << 8) | ord(key[i * 4 + 3]))

        # copy values into round key arrays
        t = 0
        j = 0
        while j < KC and t < ROUND_KEY_COUNT:
            Ke[t / BC][t % BC] = tk[j]
            Kd[ROUNDS - (t / BC)][t % BC] = tk[j]
            j += 1
            t += 1
        tt = 0
        rconpointer = 0
        while t < ROUND_KEY_COUNT:
            # extrapolate using phi (the round key evolution function)
            tt = tk[KC - 1]
            tk[0] ^= (S[(tt >> 16) & 0xFF] & 0xFF) << 24 ^  \
                     (S[(tt >>  8) & 0xFF] & 0xFF) << 16 ^  \
                     (S[ tt        & 0xFF] & 0xFF) <<  8 ^  \
                     (S[(tt >> 24) & 0xFF] & 0xFF)       ^  \
                     (rcon[rconpointer]    & 0xFF) << 24
            rconpointer += 1
            if KC != 8:
                for i in xrange(1, KC):
                    tk[i] ^= tk[i-1]
            else:
                for i in xrange(1, KC / 2):
                    tk[i] ^= tk[i-1]
                tt = tk[KC / 2 - 1]
                tk[KC / 2] ^= (S[ tt        & 0xFF] & 0xFF)       ^ \
                              (S[(tt >>  8) & 0xFF] & 0xFF) <<  8 ^ \
                              (S[(tt >> 16) & 0xFF] & 0xFF) << 16 ^ \
                              (S[(tt >> 24) & 0xFF] & 0xFF) << 24
                for i in xrange(KC / 2 + 1, KC):
                    tk[i] ^= tk[i-1]
            # copy values into round key arrays
            j = 0
            while j < KC and t < ROUND_KEY_COUNT:
                Ke[t / BC][t % BC] = tk[j]
                Kd[ROUNDS - (t / BC)][t % BC] = tk[j]
                j += 1
                t += 1
        # inverse MixColumn where needed
        for r in xrange(1, ROUNDS):
            for j in xrange(BC):
                tt = Kd[r][j]
                Kd[r][j] = U1[(tt >> 24) & 0xFF] ^ \
                           U2[(tt >> 16) & 0xFF] ^ \
                           U3[(tt >>  8) & 0xFF] ^ \
                           U4[ tt        & 0xFF]
        self.Ke = Ke
        self.Kd = Kd

    def encrypt(self, plaintext):
        if len(plaintext) != self.block_size:
            raise ValueError('wrong block length, expected ' + str(self.block_size) + ' got ' + str(len(plaintext)))
        Ke = self.Ke

        BC = self.block_size / 4
        ROUNDS = len(Ke) - 1
        if BC == 4:
            SC = 0
        elif BC == 6:
            SC = 1
        else:
            SC = 2
        s1 = shifts[SC][1][0]
        s2 = shifts[SC][2][0]
        s3 = shifts[SC][3][0]
        a = [0] * BC
        # temporary work array
        t = []
        # plaintext to ints + key
        for i in xrange(BC):
            t.append((ord(plaintext[i * 4    ]) << 24 |
                      ord(plaintext[i * 4 + 1]) << 16 |
                      ord(plaintext[i * 4 + 2]) <<  8 |
                      ord(plaintext[i * 4 + 3])        ) ^ Ke[0][i])
        # apply round transforms
        for r in xrange(1, ROUNDS):
            for i in xrange(BC):
                a[i] = (T1[(t[ i           ] >> 24) & 0xFF] ^
                        T2[(t[(i + s1) % BC] >> 16) & 0xFF] ^
                        T3[(t[(i + s2) % BC] >>  8) & 0xFF] ^
                        T4[ t[(i + s3) % BC]        & 0xFF]  ) ^ Ke[r][i]
            t = copy.copy(a)
        # last round is special
        result = []
        for i in xrange(BC):
            tt = Ke[ROUNDS][i]
            result.append((S[(t[ i           ] >> 24) & 0xFF] ^ (tt >> 24)) & 0xFF)
            result.append((S[(t[(i + s1) % BC] >> 16) & 0xFF] ^ (tt >> 16)) & 0xFF)
            result.append((S[(t[(i + s2) % BC] >>  8) & 0xFF] ^ (tt >>  8)) & 0xFF)
            result.append((S[ t[(i + s3) % BC]        & 0xFF] ^  tt       ) & 0xFF)
        return string.join(map(chr, result), '')

    def decrypt(self, ciphertext):
        if len(ciphertext) != self.block_size:
            raise ValueError('wrong block length, expected ' + str(self.block_size) + ' got ' + str(len(plaintext)))
        Kd = self.Kd

        BC = self.block_size / 4
        ROUNDS = len(Kd) - 1
        if BC == 4:
            SC = 0
        elif BC == 6:
            SC = 1
        else:
            SC = 2
        s1 = shifts[SC][1][1]
        s2 = shifts[SC][2][1]
        s3 = shifts[SC][3][1]
        a = [0] * BC
        # temporary work array
        t = [0] * BC
        # ciphertext to ints + key
        for i in xrange(BC):
            t[i] = (ord(ciphertext[i * 4    ]) << 24 |
                    ord(ciphertext[i * 4 + 1]) << 16 |
                    ord(ciphertext[i * 4 + 2]) <<  8 |
                    ord(ciphertext[i * 4 + 3])        ) ^ Kd[0][i]
        # apply round transforms
        for r in xrange(1, ROUNDS):
            for i in xrange(BC):
                a[i] = (T5[(t[ i           ] >> 24) & 0xFF] ^
                        T6[(t[(i + s1) % BC] >> 16) & 0xFF] ^
                        T7[(t[(i + s2) % BC] >>  8) & 0xFF] ^
                        T8[ t[(i + s3) % BC]        & 0xFF]  ) ^ Kd[r][i]
            t = copy.copy(a)
        # last round is special
        result = []
        for i in xrange(BC):
            tt = Kd[ROUNDS][i]
            result.append((Si[(t[ i           ] >> 24) & 0xFF] ^ (tt >> 24)) & 0xFF)
            result.append((Si[(t[(i + s1) % BC] >> 16) & 0xFF] ^ (tt >> 16)) & 0xFF)
            result.append((Si[(t[(i + s2) % BC] >>  8) & 0xFF] ^ (tt >>  8)) & 0xFF)
            result.append((Si[ t[(i + s3) % BC]        & 0xFF] ^  tt       ) & 0xFF)
        return string.join(map(chr, result), '')

def encrypt(key, block):
    return rijndael(key, len(block)).encrypt(block)

def decrypt(key, block):
    return rijndael(key, len(block)).decrypt(block)

def test():
    def t(kl, bl):
        b = 'b' * bl
        r = rijndael('a' * kl, bl)
        assert r.decrypt(r.encrypt(b)) == b
    t(16, 16)
    t(16, 24)
    t(16, 32)
    t(24, 16)
    t(24, 24)
    t(24, 32)
    t(32, 16)
    t(32, 24)
    t(32, 32)



def createByteArraySequence(seq):
    return array.array('B', seq)

def createByteArrayZeros(howMany):
    return array.array('B', [0] * howMany)

def concatArrays(a1, a2):
    return a1+a2

def bytesToString(bytes):
    return bytes.tostring()

def stringToBytes(s):
    bytes = createByteArrayZeros(0)
    bytes.fromstring(s)
    return bytes

def new(key, mode, IV):
    return Python_AES(key, mode, IV)

class Python_AES(AES):
    def __init__(self, key, mode, IV):
        AES.__init__(self, key, mode, IV, "python")
        self.rijndael = rijndael(key, 16)
        self.IV = IV

    def encrypt(self, plaintext):
        AES.encrypt(self, plaintext)

        plaintextBytes = stringToBytes(plaintext)
        chainBytes = stringToBytes(self.IV)

        #CBC Mode: For each block...
        for x in range(len(plaintextBytes)/16):

            #XOR with the chaining block
            blockBytes = plaintextBytes[x*16 : (x*16)+16]
            for y in range(16):
                blockBytes[y] ^= chainBytes[y]
            blockString = bytesToString(blockBytes)

            #Encrypt it
            encryptedBytes = stringToBytes(self.rijndael.encrypt(blockString))

            #Overwrite the input with the output
            for y in range(16):
                plaintextBytes[(x*16)+y] = encryptedBytes[y]

            #Set the next chaining block
            chainBytes = encryptedBytes

        self.IV = bytesToString(chainBytes)
        return bytesToString(plaintextBytes)

    def decrypt(self, ciphertext):
        AES.decrypt(self, ciphertext)

        ciphertextBytes = stringToBytes(ciphertext)
        chainBytes = stringToBytes(self.IV)

        #CBC Mode: For each block...
        for x in range(len(ciphertextBytes)/16):

            #Decrypt it
            blockBytes = ciphertextBytes[x*16 : (x*16)+16]
            blockString = bytesToString(blockBytes)
            decryptedBytes = stringToBytes(self.rijndael.decrypt(blockString))

            #XOR with the chaining block and overwrite the input with output
            for y in range(16):
                decryptedBytes[y] ^= chainBytes[y]
                ciphertextBytes[(x*16)+y] = decryptedBytes[y]

            #Set the next chaining block
            chainBytes = blockBytes

        self.IV = bytesToString(chainBytes)
        return bytesToString(ciphertextBytes)


class CCrypt:
    """
        Classe CCrypt
        
        Encripta e Decripta mensagens
        utilizando AES (modo CBC) e Base64
    """
    
    KEY = 'CacicBrasil'
    AES_KEY_SIZE = 32
    AES_BLOCK_SIZE = 16
    IV = 'abcdefghijklmnop'
    
    
    def __init__(self):
        self.char = '@'
        self.key = self.padding(self.KEY, self.AES_KEY_SIZE, self.char)
        self.iv = self.padding(self.IV, self.AES_BLOCK_SIZE, self.char)
    
    def encrypt(self, text):
        """Encrypta uma string com AES (CBC) e depois em BASE64"""        
        self.cipher = Python_AES(self.key, 2, self.iv)
        cifrado = self.cipher.encrypt(self.padding(text, self.AES_BLOCK_SIZE, self.char))
        return base64.encodestring(cifrado)[0:-1]
    
    def decrypt(self, text):
        """Decripta uma string convertida pelo metodo encrypt()"""
        # ER para remover o padding da string
        rm = re.compile("(?:"+ self.char +")*$")
        self.cipher = Python_AES(self.key, 2, self.iv)
        decifrado = self.cipher.decrypt(base64.decodestring(text))
        return decifrado.replace(rm.findall(decifrado)[0],'')

    def padding(self, s, tam, char):
        """Adiciona preenchimento a string"""
        s = str(s)
        origsize = len(s)
        if (origsize % tam) != 0 or origsize == 0:
            p = tam - (origsize % tam)
            s = s + (char*p);
        return s
    
    def getKeyPadding(self):
        return self.key.replace(self.KEY, "")
    
